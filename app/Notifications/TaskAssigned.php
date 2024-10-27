<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification
{
    use Queueable;

    protected $task;

    // Pass the task details through the constructor
    public function __construct($task)
    {
        $this->task = $task;
    }

    // Define the notification channels
    public function via($notifiable)
    {
        return ['database'];  // Store the notification in the database
    }

    // Define the database notification structure
    public function toDatabase($notifiable)
    {
        return [
            'message' => "You have been assigned a new task: '{$this->task->name}'",
            'task_id' => $this->task->id,
            'team_name' => $this->task->team->name ?? 'No Team', 
        ];
    }
}
