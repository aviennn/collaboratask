<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Task;

class TaskDueReminderNotification extends Notification
{
    use Queueable;

    public $task;
    public $daysRemaining;

    public function __construct(Task $task, $daysRemaining)
    {
        $this->task = $task;
        $this->daysRemaining = $daysRemaining;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // Change this to whatever channels you are using
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Task Due Reminder')
            ->line('Your task "' . $this->task->name . '" is due in ' . $this->daysRemaining . ' days.')
            ->action('View Task', url('/tasks/' . $this->task->id))
            ->line('Please complete it before the due date.');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Reminder: Task '{$this->task->name}' is due in {$this->daysRemaining} days.",
            'task_id' => $this->task->id,
            'task_name' => $this->task->name,
            'due_in_days' => $this->daysRemaining,
        ];
    }
}
