<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Models\Invitation;
use App\Notifications\TeamInvitation;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Storage;


class TeamController extends Controller
{
    public function index()
    {
        $teams = Auth::user()->usertype == 'admin' ? Team::all() : Auth::user()->teams;

        // Fetch pending invitations for the authenticated user
        $invitations = Invitation::where('invitee_id', Auth::id())
                                  ->where('status', 'pending')
                                  ->get();
    
        return view('teams.index', compact('teams', 'invitations'));    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();

        return view('teams.create', compact('users'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'members' => 'required|array', 
        'has_rewards' => 'required|boolean', 
        'team_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $imagePath = null;
    if ($request->hasFile('team_image')) {
        $imagePath = $request->file('team_image')->store('team_images', 'public');
    }

    // Create the team
    $team = Team::create([
        'name' => $request->name,
        'description' => $request->description,
        'creator_id' => Auth::id(), 
        'has_rewards' => $request->has_rewards,
        'image' => $imagePath,
    ]);

    // Add the team creator as the first member
    $team->members()->attach([Auth::id()]);

    // Loop through selected members and send an invitation
    foreach ($request->members as $userId) {
        $user = User::find($userId);

        if ($user) {
            // Create an invitation entry
            $invitation = Invitation::create([
                'team_id' => $team->id,
                'inviter_id' => Auth::id(),
                'invitee_id' => $user->id,
                'email' => $user->email,
                'status' => 'pending',
            ]);

            try {
                $user->notify(new \App\Notifications\TeamInvitation($team, Auth::user()));
            } catch (\Exception $e) {
                \Log::error("Failed to send invitation email: " . $e->getMessage());
            }
            
        }
    }

    activity()
        ->causedBy(Auth::user())
        ->performedOn($team)
        ->withProperties(['name' => $team->name])
        ->log('Created a team: ' . $team->name);

    return redirect()->route('user.teams.index')->with('success', 'Team created and invitations sent successfully.');
}




public function show(Request $request, $id)
{
    // Fetch the team along with its members and creator
    $team = Team::with(['members', 'creator'])->findOrFail($id);

    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    if ($startDate && $endDate) {
        $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Task completion metrics
    $completedTasks = $team->tasks()->where('status', 'done')->count();
    $totalTasks = $team->tasks()->count();
    $taskCompletionPercentage = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;

    // Task counts by status for reporting
    $notStarted = $team->tasks()->where('status', 'not started')->count();
    $inProgress = $team->tasks()->where('status', 'in progress')->count();
    $done = $team->tasks()->where('status', 'done')->count();

    // Task counts by priority for reporting
    $lowPriority = $team->tasks()->where('priority', 'low')->count();
    $mediumPriority = $team->tasks()->where('priority', 'medium')->count();
    $highPriority = $team->tasks()->where('priority', 'high')->count();

    // Overdue and upcoming tasks
    $overdue = $team->tasks()->where('due_date', '<', now())->count();
    $dueThisWeek = $team->tasks()->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])->count();
    $dueToday = $team->tasks()->whereDate('due_date', now())->count();

    // Check if the user is authorized to view the team
    if (Auth::user()->usertype != 'admin' && !$team->members->contains(Auth::id())) {
        return redirect('/dashboard')->with('error', 'Unauthorized access to this team.');
    }

    // Fetch tasks for admin or team creator that are done and pending approval
    if (Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id) {
        $tasks = $team->tasks()
            ->where('status', 'done')
            ->where('approval_status', 'pending')
            ->get();
    } else {
        // Regular users see only their own tasks
        $tasks = Task::where('team_id', $team->id)
            ->where('user_id', Auth::id())
            ->get();
    }

    $tasksPendingApproval = [];
    $tasksToGrade = [];
    
    if (Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id) {
        // Admin or creator fetches tasks pending approval
        $tasksPendingApproval = $team->tasks()
            ->where('status', 'done')
            ->where('approval_status', 'pending')
            ->get();

        // Fetch tasks needing grading only if the team has rewards enabled
        if ($team->has_rewards) {
            $tasksToGrade = $team->tasks()
                ->where('approval_status', 'approved')
                ->where('is_graded', false)
                ->get();
        }
    } else {
        // Regular users only see their own tasks
        $tasks = $team->tasks()
            ->where('user_id', Auth::id())
            ->get();
    }

    // Pass the data to the view
    return view('teams.show', compact(
        'team', 
        'tasks', 
        'taskCompletionPercentage',
        'notStarted',
        'inProgress',
        'done',
        'lowPriority',
        'mediumPriority',
        'highPriority',
        'overdue',
        'dueThisWeek',
        'dueToday',
        'tasksPendingApproval',
        'tasksToGrade'
    ));
}



    public function edit($id)
{
    $team = Team::with('members')->findOrFail($id);

    // Check if the user is either admin or the creator of the team
    if (Auth::user()->usertype != 'admin' && Auth::id() != $team->creator_id) {
        return redirect()->route('user.teams.index')->with('error', 'You do not have permission to edit this team.');
    }

    $users = User::where('id', '!=', Auth::id())->get();

    return view('teams.edit', compact('team', 'users'));
}

public function update(Request $request, $id)
{
    $team = Team::findOrFail($id);

    // Check user permission
    if (Auth::user()->usertype != 'admin' && Auth::id() != $team->creator_id) {
        return redirect()->route('user.teams.index')->with('error', 'You do not have permission to update this team.');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'members' => 'required|array',
        'has_rewards' => 'required|boolean', // Add validation for the rewards option
        'team_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($request->hasFile('team_image')) {
        // Delete old image if exists
        if ($team->image) {
            Storage::delete('public/' . $team->image);
        }
        $imagePath = $request->file('team_image')->store('team_images', 'public');
        $team->image = $imagePath;
    }


    // Get the original members list (excluding the creator)
    $originalMembers = $team->members->pluck('id')->toArray();
    $originalMembersWithoutCreator = array_diff($originalMembers, [Auth::id()]);

    // Get the updated members (from the request, excluding the creator)
    $updatedMembers = $request->members;
    $updatedMembersWithoutCreator = array_diff($updatedMembers, [Auth::id()]);

    // First, update the team data
    $team->update([
        'name' => $request->name,
        'description' => $request->description,
        'has_rewards' => $request->has_rewards,
    ]);

    // Now check for changes (after update)
    $changes = [];

    if ($team->wasChanged('name')) {
        $changes[] = "Team name changed from '{$team->getOriginal('name')}' to '{$team->name}'";
    }
    if ($team->wasChanged('description')) {
        $changes[] = "Description updated";
    }
    if ($team->wasChanged('has_rewards')) {
        $changes[] = "Rewards option changed from '" . ($team->getOriginal('has_rewards') ? 'enabled' : 'disabled') . "' to '" . ($team->has_rewards ? 'enabled' : 'disabled') . "'";
    }

    // Sync members (always keep the creator in the team)
    $team->members()->sync(array_merge($updatedMembers, [Auth::id()]));

    // Find members added or removed based on the original members list (excluding the creator)
    $addedMembers = array_diff($updatedMembersWithoutCreator, $originalMembersWithoutCreator);
    $removedMembers = array_diff($originalMembersWithoutCreator, $updatedMembersWithoutCreator);

    // Log added members
    if (!empty($addedMembers)) {
        $addedNames = User::whereIn('id', $addedMembers)->pluck('name')->toArray();
        $changes[] = "Members added: " . implode(', ', $addedNames);
    }

    // Log removed members
    if (!empty($removedMembers)) {
        $removedNames = User::whereIn('id', $removedMembers)->pluck('name')->toArray();
        $changes[] = "Members removed: " . implode(', ', $removedNames);
    }

    // Log activity if there are any changes
    if (!empty($changes)) {
        activity()
            ->causedBy(Auth::user())
            ->performedOn($team)
            ->withProperties(['changes' => $changes])
            ->log('Updated team: ' . $team->name . ' with changes: ' . implode(', ', $changes));
    }

    return redirect()->route('user.teams.index')->with('success', 'Team updated successfully.');
}




    


    public function destroy($id)
{
    $team = Team::findOrFail($id);

    if (Auth::user()->usertype != 'admin' && $team->creator_id != Auth::id()) {
        return redirect()->route('user.teams.index')->with('error', 'You do not have permission to delete this team.');
    }

    try {
        $teamName = $team->name; // Store the team name for the flash message

        // Detach all members from the team
        $team->members()->detach();

        // Delete the team
        $team->delete();

        // Redirect with success message including the team name
        return redirect()->route('user.teams.index')->with('success', "Team '{$teamName}' deleted successfully.");

    } catch (\Exception $e) {
        // Log the error for debugging
        \Log::error('Error deleting team: ' . $e->getMessage());

        // Redirect back with an error message
        return redirect()->route('user.teams.index')->with('error', 'There was an issue deleting the team. Please try again.');
    }
}


    public function viewAnalytics($team_id, $user_id)
{
    $team = Team::findOrFail($team_id);
    $user = User::findOrFail($user_id);

    // Task Statistics
    $totalTasks = $team->tasks()->where('user_id', $user_id)->count();
    $completedTasks = $team->tasks()->where('user_id', $user_id)->where('status', 'done')->count();
    $inProgressTasks = $team->tasks()->where('user_id', $user_id)->where('status', 'in progress')->count();
    $notStartedTasks = $team->tasks()->where('user_id', $user_id)->where('status', 'not started')->count();

    // Tasks completed on time vs. overdue tasks
    $tasksCompletedOnTime = $team->tasks()->where('user_id', $user_id)
        ->where('status', 'done')
        ->whereColumn('due_date', '>=', 'date_completed')
        ->count();

    $tasksOverdue = $team->tasks()->where('user_id', $user_id)
        ->where('status', 'done')
        ->whereColumn('due_date', '<', 'date_completed')
        ->count();

    // Task completion rate for each user (completed / total)
    $completionRate = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;

    // Average time taken to complete tasks
    $averageCompletionTime = $team->tasks()->where('user_id', $user_id)
        ->whereNotNull('date_started')
        ->whereNotNull('date_completed')
        ->get()
        ->map(function ($task) {
            return $task->date_started->diffInHours($task->date_completed);
        })->avg();

    // Task priorities handled by users (high, medium, low)
    $highPriorityTasks = $team->tasks()->where('user_id', $user_id)->where('priority', 'high')->count();
    $mediumPriorityTasks = $team->tasks()->where('user_id', $user_id)->where('priority', 'medium')->count();
    $lowPriorityTasks = $team->tasks()->where('user_id', $user_id)->where('priority', 'low')->count();

    // Points system (based on tasks or rewards)
    $totalPoints = $team->tasks()->where('user_id', $user_id)->sum('points');

    // Count tasks based on grading
    $countGood = $team->tasks()->where('user_id', $user_id)->where('grade', 'good')->count();
    $countVeryGood = $team->tasks()->where('user_id', $user_id)->where('grade', 'very good')->count();
    $countExcellent = $team->tasks()->where('user_id', $user_id)->where('grade', 'excellent')->count();

    $redeemedRewards = $user->rewards()->where('team_id', $team_id)->get(); // Assuming you have a rewards() relationship

    // Pass data to the view
    return view('teams.analytics', compact(
        'user', 'team', 'totalTasks', 'completedTasks', 'inProgressTasks', 'notStartedTasks',
        'tasksCompletedOnTime', 'tasksOverdue', 'completionRate', 'averageCompletionTime',
        'highPriorityTasks', 'mediumPriorityTasks', 'lowPriorityTasks', 'totalPoints',
        'countGood', 'countVeryGood', 'countExcellent', 'redeemedRewards'
    ));
}

}