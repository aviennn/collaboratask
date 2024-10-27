<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskOverDueNotification extends Notification
{
    use Queueable;

    protected $task;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];  // Send via email and store in database
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Task Overdue: ' . $this->task->name)
                    ->line('The task "' . $this->task->name . '" was due on ' . $this->task->due_date->format('M d, Y') . '.')
                    ->action('View Task', url('/tasks/' . $this->task->id))
                    ->line('Please check the task and take appropriate action.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'The task "' . $this->task->name . '" is overdue. It was due on ' . $this->task->due_date->format('M d, Y') . '.',
            'task_id' => $this->task->id,
        ];
    }
}
