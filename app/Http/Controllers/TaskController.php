<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\Checklist;
use App\Models\Team;
use Illuminate\Support\Facades\Storage;
use App\Notifications\TaskAssigned;
use App\Models\User;
use App\Notifications\TaskProgressUpdated;
use Spatie\Activitylog\Models\Activity;
use App\Events\TaskCreated;
use App\Notifications\BadgeUnlocked;
use App\Models\Badge;
use App\Services\RewardService;


class TaskController extends Controller
{
    protected $rewardService;
    // Inject the RewardService into the controller
    public function __construct(RewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }
    public function index()
    {
        if (Auth::user()->usertype == 'admin') {
            // Admin: fetch all tasks
            $allTasks = Task::with('user', 'team')->get();
            
            // Fetch separated tasks for Kanban view
            $tasksNotStarted = Task::where('status', 'not started')->with('user')->get();
            $tasksInProgress = Task::where('status', 'in progress')->with('user')->get();
            $tasksDone = Task::where('status', 'done')->with('user')->get();
    
            // Admin: fetch all tasks assigned to teams
            $teamTasks = Task::whereNotNull('team_id')->with('team', 'user')->get();
        } else {
            // Regular user: fetch only their tasks
            $allTasks = Task::where('user_id', Auth::id())->with('user')->get();
    
            // Fetch separated tasks for Kanban view
            $tasksNotStarted = Task::where('user_id', Auth::id())->where('status', 'not started')->get();
            $tasksInProgress = Task::where('user_id', Auth::id())->where('status', 'in progress')->get();
            $tasksDone = Task::where('user_id', Auth::id())->where('status', 'done')->get();
    
            // Fetch team tasks assigned to the user within teams they belong to
            $teamTasks = Task::whereHas('team.members', function ($query) {
                $query->where('user_id', Auth::id());  // Get tasks for teams the user is a member of
            })
            ->where('user_id', Auth::id())  // Only tasks assigned to the user
            ->with('team', 'user')->get();  // Load the team and user data
        }
    
        // Pass both user and team tasks to the view
        return view('tasks.index', compact('allTasks', 'tasksNotStarted', 'tasksInProgress', 'tasksDone', 'teamTasks'));
    }
    
    public function assignTask(Request $request, Task $task)
{
    // Assign the task to the user
    $task->user_id = $request->input('user_id');  // Ensure this input is properly set
    $task->save();

    // Find the assigned user
    $assignedUser = User::find($task->user_id);

    // Notify the user with Laravel's built-in notification system
    $assignedUser->notify(new TaskAssigned($task));

    activity()
        ->causedBy(Auth::user())
        ->performedOn($task)
        ->withProperties([
            'task_name' => $task->name,
            'assigned_to' => $assignedUser->name
        ])
        ->log('Assigned task: ' . $task->name . ' to ' . $assignedUser->name);

    return redirect()->back()->with('message', 'Task assigned and notification sent.');
}



    public function calendarView()
{
    return view('tasks.calendar');  // You'll create this blade file next
}

public function fetchEvents()
{
    // Check if the logged-in user is an admin
    if (auth()->user()->usertype == 'admin') {
        // Fetch all tasks if the user is an admin
        $tasks = Task::all();
    } else {
        // Fetch only tasks assigned to the logged-in user or tasks created by the user
        $tasks = Task::where('user_id', auth()->id())  // Fetch tasks assigned to the user
            ->orWhere(function($query) {
                // Also fetch team tasks only if the user is assigned to them
                $query->whereHas('team', function ($query) {
                    $query->whereHas('members', function ($query) {
                        $query->where('user_id', auth()->id());  // Filter by team members
                    });
                })
                ->where('user_id', auth()->id());  // Ensure that the user is the assignee of the team task
            })
            ->get();
    }

    // Prepare tasks for FullCalendar
    $events = [];

    foreach ($tasks as $task) {
        $events[] = [
            'id' => $task->id,
            'title' => $task->name,
            'start' => $task->due_date->toDateString(),
            'description' => $task->description,
            'status' => $task->status,
            'due_date' => $task->due_date->toDateString(),
            'priority' => $task->priority,  // Include priority for coloring the tasks
            'points' => $task->points,
            'checklist' => $task->checklists->map(function($checklist) {
                return [
                    'item' => $checklist->item,
                    'completed' => $checklist->completed,  // Assuming there's a 'completed' field
                ];
            }),
            'attachments' => $task->attachments->map(function($attachment) {
                return [
                    'file_path' => $attachment->file_path,
                ];
            }),
            'url' => route('user.tasks.show', $task->id),  // Link to task details page
        ];
    }

    // Return the events as JSON for FullCalendar
    return response()->json($events);
}


public function updateDueDate(Request $request, $id)
{
    $request->validate([
        'due_date' => 'required|date',
    ]);

    $task = Task::findOrFail($id);

    if (Auth::user()->usertype != 'admin' && $task->user_id != Auth::id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $task->due_date = $request->due_date;
    $task->save();

    return response()->json(['status' => 'success', 'message' => 'Due date updated successfully']);
}


public function storeFromCalendar(Request $request)
{
    try {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|string|in:low,medium,high',
            'attachments.*' => 'nullable|file|max:2048',
            'checklists' => 'nullable|array',  // Validate checklist as array
            'checklists.*' => 'nullable|string',  // Each checklist item must be a string (can be nullable for filtering)
        ]);

        // Create a new task
        $task = new Task();
        $task->name = $validatedData['name'];
        $task->description = $validatedData['description'] ?? '';
        $task->due_date = $validatedData['due_date'];
        $task->user_id = Auth::id();
        $task->status = 'not started';
        $task->priority = $validatedData['priority'];
        $task->save();

        // Handle checklist items if provided
        if ($request->has('checklists')) {
            $checklistItems = array_filter($request->checklists); // Filter out empty checklist items
            foreach ($checklistItems as $checklistItem) {
                if (!empty($checklistItem)) {
                    $task->checklists()->create(['item' => $checklistItem]);
                }
            }
        }

        // Handle file uploads if provided
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs('attachments', $filename, 'public');
                $task->attachments()->create(['file_path' => $path]);
            }
        }

        return response()->json(['status' => 'success', 'task' => $task]);

    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
}

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'priority' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'attachments.*' => 'nullable|file|max:2048',
            'checklists' => 'nullable|array',
            'checklists.*' => 'nullable|string|max:255',
        ]);
    
        try {
            // Create the task
            $task = new Task();
            $task->user_id = Auth::id();
            $task->name = $request->name;
            $task->priority = $request->priority;
            $task->description = $request->description;
            $task->status = 'not started';
            $task->due_date = $request->due_date;
            $task->save();
    
            // Handle checklists
            if ($request->has('checklists')) {
                foreach ($request->checklists as $item) {
                    if (!empty($item)) {
                        $task->checklists()->create(['item' => $item]);
                    }
                }
            }
    
            // Handle file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $filename = $file->getClientOriginalName();
                    $path = $file->storeAs('attachments', $filename, 'public');
                    $task->attachments()->create(['file_path' => $path]);
                }
            }
    
            // Log activity
            activity()
                ->causedBy(Auth::user())
                ->performedOn($task)
                ->withProperties(['name' => $task->name])
                ->log('Created a task: ' . $task->name);
    
            // Broadcast the TaskCreated event
            broadcast(new TaskCreated($task))->toOthers();
    
            // Return with success message
            return redirect()->route('user.tasks.index')->with('success', 'Task ' . $task->name . ' created successfully!');

            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Task creation failed: ' . $e->getMessage());
            
            // Return with an error flash message
            return redirect()->back()->with('error', 'There was an error creating the task. Please try again.');
        }
    }
    

    public function edit($id)
    {
        $task = Task::findOrFail($id);
        $users = User::all(); // Fetch all users to allow assignment

        if (Auth::user()->usertype != 'admin' && $task->user_id != Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, $id)
{
    // Find the task by ID
    $task = Task::findOrFail($id);

    // Restrict updates if the task is approved or graded
    if ($task->approval_status === 'approved' || $task->is_graded) {
        return redirect()->back()->with('error', 'This task has been approved and/or graded, and can no longer be updated.');
    }

    // Validate the incoming request data
    $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'priority' => 'sometimes|required|string',
        'description' => 'nullable|string',
        'status' => 'required|string',
        'due_date' => 'sometimes|required|date',
        'attachments.*' => 'nullable|file|max:2048',
        'assignee' => 'sometimes|exists:users,id',
    ]);

    // Check if the user is admin, task creator, or team creator
    $isAdminOrCreator = Auth::user()->usertype == 'admin' || ($task->team && Auth::id() == $task->team->creator_id);
    $isOwnTask = !$task->team && Auth::id() == $task->user_id;

    // Allow only if the user is admin, task creator, or team creator
    if ($isAdminOrCreator || $isOwnTask) {
        if ($request->has('assignee') && $isAdminOrCreator) {
            $task->user_id = $request->assignee;
        }
        if ($request->has('name')) {
            $task->name = $request->name;
        }
        if ($request->has('priority')) {
            $task->priority = $request->priority;
        }
        if ($request->has('due_date')) {
            $task->due_date = $request->due_date;
        }
    }

    if ($request->has('description')) {
        $task->description = $request->description;
    }

    // Automatically set date_started when task is marked 'in progress'
    if ($request->status == 'in progress' && !$task->date_started) {
        $task->date_started = now();
    }

    // Automatically set `date_completed` and `approval_status` when task is marked 'done'
    if ($request->status == 'done' && !$task->date_completed) {
        $task->date_completed = now();
        $task->approval_status = 'pending';  // Set approval status to 'pending'

        // Calculate and store the duration
        if ($task->date_started) {
            $duration = $task->date_started->diffForHumans($task->date_completed, true);
            $task->duration = $duration;
        }
    } elseif ($request->status != 'done') {
        $task->date_completed = null;  // Reset date_completed if status is not 'done'
        $task->duration = null;  // Reset duration
    }

    // Save the new status before checking for changes
    $originalStatus = $task->getOriginal('status'); // Get the original status
    $task->status = $request->status;
    $task->save();

    // Now, check for any changes after saving the task
    $changes = [];
    if ($task->wasChanged('name')) {
        $changes[] = "Task name changed from '{$task->getOriginal('name')}' to '{$task->name}'";
    }
    if ($task->wasChanged('priority')) {
        $changes[] = "Priority changed from '{$task->getOriginal('priority')}' to '{$task->priority}'";
    }
    if ($task->wasChanged('due_date')) {
        $changes[] = "Due date changed from '{$task->getOriginal('due_date')}' to '{$task->due_date}'";
    }
    if ($originalStatus !== $task->status) {
        $changes[] = "Status changed from '{$originalStatus}' to '{$task->status}'";
    }
    if ($task->wasChanged('description')) {
        $changes[] = "Description updated";
    }

    // Log the activity with detailed changes
    if (!empty($changes)) {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($task)
            ->withProperties(['changes' => $changes])
            ->log('Updated task: ' . $task->name . ' with changes: ' . implode(', ', $changes));
    }

     // Send notification when the status changes
     if ($originalStatus !== $task->status && $task->team && $task->team->creator) {
        $teamLeader = $task->team->creator;
        $teamLeader->notify(new TaskProgressUpdated($task));  // Send the notification
    }
    $redirectRoute = $task->team ? route('user.teams.show', $task->team_id) : route('user.tasks.index');

    return redirect($redirectRoute)->with('success', 'Task updated successfully.');
}



    


public function destroy($id)
{
    $task = Task::findOrFail($id);
    $user = Auth::user();

    // Check if the user is authorized to delete the task
    if ($user->usertype == 'admin' || $task->user_id == $user->id || $task->team->members->contains($user)) {
        activity()
            ->causedBy($user)
            ->performedOn($task)
            ->withProperties(['name' => $task->name, 'description' => $task->description])
            ->log('Task deleted: ' . $task->name);

        $task->delete();
        return response()->json(['success' => 'Task deleted successfully']);
    }

    // Unauthorized action
    return response()->json(['error' => 'Unauthorized action.'], 403);
}



public function show($id)
{
    $task = Task::findOrFail($id);

    // Check authorization
    if (Auth::user()->usertype != 'admin' && $task->user_id != Auth::id()) {
        if (!$task->team || !$task->team->members->contains(Auth::id())) {
            return redirect('/dashboard')->with('error', 'Unauthorized access to this task.');
        }
    }

    // Fetch only team members if the task belongs to a team, otherwise fetch the task owner
    $users = $task->team ? $task->team->members : collect([$task->user]);

    return response()
        ->view('tasks.show', compact('task', 'users'))
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
}







public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|string',
    ]);

    $task = Task::findOrFail($id);

    // Check if the user is authorized to update the task status
    if (Auth::user()->usertype != 'admin' && $task->user_id != Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    // Capture the original status before the update
    $originalStatus = $task->getOriginal('status');

    // Update task status logic based on status type
    if ($request->status == 'in progress' && !$task->date_started) {
        $task->date_started = now();  // Record the start time when the task moves to "in progress"
    } elseif ($request->status == 'done' && !$task->date_completed) {
        $task->date_completed = now();  // Record the completion time when the task moves to "done"

        // Calculate the duration if the task has a start date
        if ($task->date_started) {
            $task->duration = $task->date_started->diffForHumans($task->date_completed, true);  // Human-readable duration
        }

        // Set approval status to pending for team leaders to review
        $task->approval_status = 'pending';

        // Automatically trigger reward logic upon task completion
        $rewardService = new RewardService();
        $rewardService->handleTaskCompletionRewards($task->user);  // Call the reward service
    } elseif ($request->status == 'not started') {
        // Reset start and completion dates if moving back to "not started"
        $task->date_started = null;
        $task->date_completed = null;
        $task->duration = null;
    }

    // Save the new status
    $task->status = $request->status;
    $task->save();

    // Capture changes in the status and log the meaningful change
    $changes = [];
    if ($originalStatus !== $task->status) {
        $changes[] = "Status changed from '{$originalStatus}' to '{$task->status}'";
    }

    // Log the activity with detailed changes
    if (!empty($changes)) {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($task)
            ->withProperties(['changes' => $changes])
            ->log('Updated task: ' . $task->name . ' with changes: ' . implode(', ', $changes));
    }

    // Notify the team leader about the task status update if it's part of a team
    if ($task->team && $task->team->creator) {
        $teamLeader = $task->team->creator;
        $teamLeader->notify(new TaskProgressUpdated($task));  // Send the notification
    }

    // Return the updated data for the front-end
    return response()->json([
        'message' => 'Task status updated successfully.',
        'date_started' => $task->date_started ? $task->date_started->format('Y-m-d H:i:s') : null,
        'duration' => $task->duration ?? 'N/A',
        'status' => ucfirst($task->status),
        'approval_status' => ucfirst($task->approval_status), // For displaying in UI
    ]);
}

protected function awardXPForTaskCompletion($user, $task)
{
    $xpGained = $task->points;  // Assuming task points = XP

    $user->xp += $xpGained;  // Add XP to the user
    $this->checkLevelUp($user);  // Check if the user should level up

    $user->save();  // Save the updated user
}

protected function checkLevelUp($user)
{
    $xpThreshold = 100;  // Set XP required per level, adjust this as needed

    // While the user's XP exceeds the threshold, level them up
    while ($user->xp >= $xpThreshold) {
        $user->xp -= $xpThreshold;  // Subtract XP threshold
        $user->level++;             // Increase the user’s level
    }
}

    public function downloadAttachment($id, $attachmentId)
    {
        $task = Task::findOrFail($id);
        $attachment = TaskAttachment::findOrFail($attachmentId);

        if (Auth::user()->usertype != 'admin' && $task->user_id != Auth::id()) {
            abort(403);
        }

        if (!$attachment) {
            abort(404);
        }

        return Storage::disk('public')->download($attachment->file_path);
    }

    public function removeAttachment($id, $attachmentId)
    {
        $task = Task::findOrFail($id);
        $attachment = TaskAttachment::findOrFail($attachmentId);

        if (Auth::user()->usertype != 'admin' && $task->user_id != Auth::id()) {
            abort(403);
        }

        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return redirect()->route('user.tasks.show', $task->id)->with('success', 'Attachment removed successfully.');
    }

    public function addChecklistItem(Request $request, $taskId)
    {
        $request->validate([
            'item' => 'required|string|max:255',
        ]);

        $task = Task::findOrFail($taskId);
        $task->checklists()->create([
            'item' => $request->item,
        ]);

        return redirect()->route('user.tasks.show', $taskId)->with('success', 'Checklist item added successfully.');
    }

    public function updateChecklistItem(Request $request, $taskId, $checklistId)
    {
        // Validate the input
        $request->validate([
            'is_completed' => 'required|boolean',
        ]);
    
        // Find the checklist item and ensure it belongs to the task
        $checklist = Checklist::where('task_id', $taskId)->findOrFail($checklistId);
        
        // Fetch the task to check user permissions
        $task = $checklist->task;
    
        // Check if the user is authorized to update the checklist item
        if (Auth::id() !== $task->user_id && 
            Auth::user()->usertype !== 'admin' && 
            (!($task->team && Auth::id() === $task->team->creator_id))
        ) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        // Update the checklist item
        $checklist->update([
            'is_completed' => $request->is_completed,
        ]);
    
        return response()->json(['message' => 'Checklist item updated successfully.']);
    }
    

    public function deleteChecklistItem($taskId, $checklistId)
    {
        $checklist = Checklist::where('task_id', $taskId)->findOrFail($checklistId);
        $checklist->delete();

        return redirect()->route('user.tasks.show', $taskId)->with('success', 'Checklist item deleted successfully.');
    }

    public function storeForTeam(Request $request, $teamId)
    {
        $team = Team::findOrFail($teamId);
    
        // Check if the user is the team creator or an admin
        if (Auth::user()->usertype != 'admin' && Auth::id() != $team->creator_id) {
            return redirect()->route('teams.show', $teamId)->with('error', 'Unauthorized to assign tasks to this team.');
        }
    
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'priority' => 'required|string',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'attachments.*' => 'nullable|file|max:2048',
            'checklists' => 'nullable|array',
            'checklists.*' => 'nullable|string|max:255',
            'assignee' => 'required|exists:users,id',  // Ensure the assignee exists in the users table
        ]);
    
        // Create a new task
        $task = new Task();
        $task->user_id = $request->assignee;  // Assign task to the selected user
        $task->name = $request->name;
        $task->priority = $request->priority;
        $task->description = $request->description;
        $task->status = 'not started';  // Set the initial status to "Not Started"
        $task->due_date = $request->due_date;
        $task->team_id = $teamId;
        $task->save();
    
        // Handle checklists
        if ($request->has('checklists')) {
            foreach ($request->checklists as $item) {
                if (!empty($item)) {
                    $task->checklists()->create(['item' => $item]);
                }
            }
        }
    
        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs('attachments', $filename, 'public');
                $task->attachments()->create(['file_path' => $path]);
            }
        }
    
        // Notify the assigned user with Laravel's built-in notification system
        $assignedUser = User::find($task->user_id);
        if ($assignedUser) {
            $assignedUser->notify(new TaskAssigned($task));  // Send a notification to the assigned user
        }
    
        return redirect()->route('user.teams.show', $teamId)->with('success', 'Task created and assigned successfully.');
    }
    
    public function grade(Request $request, $id)
{
    $task = Task::findOrFail($id);

    // Fetch the associated team from the task
    $team = $task->team;

    // Check if the user is an admin or team creator
    $isAdminOrCreator = Auth::user()->usertype == 'admin' || ($team && Auth::id() == $team->creator_id);

    // Ensure that the team exists and has rewards enabled before grading
    if (!$team || !$team->has_rewards) {
        return redirect()->route('user.teams.show', $task->team_id)->with('error', 'Grading is not allowed for teams without points and rewards.');
    }

    // Check if the task has already been graded
    if ($task->is_graded) {
        return redirect()->route('user.teams.show', $task->team_id)->with('error', 'This task has already been graded.');
    }

    // Only allow grading if the task has been approved
    if ($isAdminOrCreator && $task->approval_status == 'approved') {
        $request->validate([
            'grade' => 'required|string|in:good,very good,excellent',
        ]);

        // Assign points based on the grade
        $pointsToAdd = 0;
        switch ($request->grade) {
            case 'excellent':
                $pointsToAdd = 50;  // Add 50 points
                break;
            case 'very good':
                $pointsToAdd = 30;  // Add 30 points
                break;
            case 'good':
                $pointsToAdd = 10;  // Add 10 points
                break;
        }

        // Add points to the task and user's total points
        $task->points += $pointsToAdd;
        $task->user->total_points += $pointsToAdd;
        $task->user->save(); // Save the updated points for the user

        // Update the grade field and mark the task as graded
        $task->grade = $request->grade;
        $task->is_graded = true;
        $task->save();  // Save the task with updated points and grade

        activity()
            ->causedBy(Auth::user())
            ->performedOn($task)
            ->withProperties([
                'task_name' => $task->name,
                'grade' => $request->grade,
                'points' => $pointsToAdd
            ])
            ->log('Task graded: ' . $task->name . ' as ' . $request->grade);

        return redirect()->route('user.teams.show', $task->team_id)->with('success', 'Task graded successfully.');
    }

    return redirect()->route('user.teams.show', $task->team_id)->with('error', 'Unauthorized to grade this task.');
}

    



    public function approveOrReject(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $team = $task->team; // Get the associated team
        $isAdminOrCreator = Auth::user()->usertype == 'admin' || ($team && Auth::id() == $team->creator_id);
    
        if (!$isAdminOrCreator) {
            abort(403, 'Unauthorized action.');
        }
    
        $request->validate([
            'approval_status' => 'required|string|in:approved,rejected',
            'rejection_remarks' => 'nullable|string',
        ]);
    
        // Update the task's approval status
        $task->approval_status = $request->approval_status;
    
        // If the task is approved and the team has rewards enabled, calculate and add points
        if ($task->approval_status == 'approved' && $team->has_rewards) {
            $task->points += $task->calculatePoints();
    
            // Award XP for the task once it has been approved
            $this->awardXPForTaskCompletion($task->user, $task);
        } elseif ($task->approval_status == 'rejected') {
            // If the task is rejected, add rejection remarks
            $task->rejection_remarks = $request->rejection_remarks;
        }
    
        // Save the task with the updated approval status (and points if applicable)
        $task->save();
    
        activity()
        ->causedBy(Auth::user())
        ->performedOn($task)
        ->withProperties([
            'task_name' => $task->name,
            'approval_status' => $task->approval_status,
            'rejection_remarks' => $request->rejection_remarks ?? null
        ])
        ->log('Task ' . $task->approval_status . ': ' . $task->name);
        
        return redirect()->route('user.teams.show', $task->team_id)->with('success', 'Task approval status updated successfully.');
    }
    

    public function fetchTasksByPriorityStatus()
{
    $tasks = Task::where('user_id', Auth::id()) // Fetch only user's tasks
        ->get()
        ->groupBy(function($task) {
            if ($task->priority == 'high' && ($task->status == 'not started' || $task->status == 'in progress')) {
                return 'do-first';
            } elseif ($task->priority == 'medium' && ($task->status == 'not started' || $task->status == 'in progress')) {
                return 'schedule';
            } elseif ($task->priority == 'high' && $task->status == 'done') {
                return 'delegate';
            } elseif ($task->priority == 'low' && ($task->status == 'in progress' || $task->status == 'done')) {
                return 'delegate';
            } else {
                return 'eliminate';
            }
        });

    return response()->json($tasks);
}

    public function updateTaskCategory(Request $request, Task $task)
    {
        // Validate that the input has valid priority and status values
        $validatedData = $request->validate([
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:not started,in progress,done',
        ]);
    
        // Update the task's priority and status
        $task->priority = $validatedData['priority'];
        $task->status = $validatedData['status'];
        $task->save();
    
        return response()->json(['success' => true, 'message' => 'Task updated successfully.']);
    }
    

}
