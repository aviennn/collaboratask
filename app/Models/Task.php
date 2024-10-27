<?php

namespace App\Models;

use App\Notifications\TaskDueReminderNotification; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Services\RewardService;
use App\Notifications\TaskOverdueNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes; 

    protected $fillable = [
        'user_id',
        'name',
        'priority',
        'status',
        'due_date',
        'description',
        'date_started',
        'points',
        'grade',
        'date_completed',
    ];

    protected $casts = [
        'due_date' => 'date',  // Ensure due_date is treated as a Carbon date object
        'date_started' => 'datetime',
        'date_completed' => 'datetime',
    ];


    public function feedbacks()
{
    return $this->hasMany(Feedback::class);
}
    protected $dates = ['deleted_at'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function checklists()
    {
        return $this->hasMany(Checklist::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignments()
    {
        return $this->belongsToMany(User::class, 'task_assignments', 'task_id', 'user_id');
    }

    public function assignedTasks()
    {
        return $this->belongsToMany(Task::class, 'task_assignments', 'user_id', 'task_id');
    }

    public function calculatePoints()
    {
        switch ($this->priority) {
            case 'high':
                return 55;
            case 'medium':
                return 35;
            case 'low':
                return 15;
            default:
                return 0;
        }
    }

    public function getDurationAttribute()
    {
        if ($this->date_started && $this->date_completed) {
            $duration = $this->date_completed->diff($this->date_started);

            $parts = [];

            if ($duration->d > 0) {
                $parts[] = $duration->d . 'd';
            }
            if ($duration->h > 0) {
                $parts[] = $duration->h . 'hr';
            }
            if ($duration->i > 0) {
                $parts[] = $duration->i . 'min';
            }
            if ($duration->s > 0 || empty($parts)) { // Show seconds even if it's the only part
                $parts[] = $duration->s . 's';
            }

            return implode(' ', $parts);
        }

        return null;
    }

    // Trigger reward logic when task is updated (e.g., when it is completed)
    protected static function booted()
    {
        static::updated(function ($task) {
            // Check if the task was marked as completed
            if ($task->date_completed && !$task->getOriginal('date_completed')) {
                // Instantiate RewardService to handle reward assignment
                $rewardService = new RewardService();
                $rewardService->handleTaskCompletionRewards($task->user);
            }
        });
    }

    public static function sendDueDateReminder()
    {
        $today = Carbon::today();
        
        // Get tasks that are due in 1 week, 3 days, or tomorrow
        $tasksDueSoon = self::whereBetween('due_date', [
            $today->copy()->addDay()->format('Y-m-d'),
            $today->copy()->addWeek()->format('Y-m-d')
        ])->get();
        
        if ($tasksDueSoon->isEmpty()) {
            echo "No tasks found with upcoming due dates.\n";
        } else {
            foreach ($tasksDueSoon as $task) {
                // Calculate the difference in days between the due date and today
                $daysRemaining = $today->diffInDays($task->due_date, false);
    
                // Process tasks that are due in exactly 1 week, 3 days, or tomorrow
                if (in_array($daysRemaining, [1, 3, 7])) {
                    echo "Task Found: " . $task->name . " is due in " . $daysRemaining . " days.\n";
                    
                    // Send notification to the user
                    $task->user->notify(new TaskDueReminderNotification($task, $daysRemaining));
    
                    // Output to confirm notification sent
                    echo "Notification sent to user: " . $task->user->name . "\n";
                }
            }
        }
    }
    
    public static function sendOverdueNotifications()
    {
        // Get tasks where the due date is in the past but the task is not completed
        $overdueTasks = self::where('due_date', '<', Carbon::today())
            ->where('status', '!=', 'done') // Task is not marked as 'done'
            ->get();

            foreach ($overdueTasks as $task) {
                // Notify the assigned user about the overdue task
                if ($task->assignee) {
                    $task->assignee->notify(new TaskOverdueNotification($task));
                }
            
                // Log the task reminder for debugging
                Log::info("Sent overdue notification for Task: {$task->name}, due on: {$task->due_date}");
        }
    }

      // Urgency is based on how close the due date is (3 days or less is urgent)
public function getUrgencyAttribute()
{
    $dueDate = $this->due_date;
    $today = Carbon::now();

    // Consider the task urgent if it's due in 3 days or overdue
    return $dueDate->diffInDays($today, false) <= 3 ? 1 : 0;
}
 
    // Importance is based on task priority
public function getImportanceAttribute()
{
    // Consider the task important if its priority is 'high'
    return $this->priority === 'high' ? 1 : 0;
}
 
     public function isPersonal()
     {
         return $this->team_id === null;
     }

    
}
