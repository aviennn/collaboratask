<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BadgeUnlocked extends Notification
{
    use Queueable;

    protected $badge;

    /**
     * Create a new notification instance.
     *
     * @param $badge
     */
    public function __construct($badge)
    {
        $this->badge = $badge;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];  // Ensure 'database' is included
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
                    ->subject('You’ve unlocked a new badge!')
                    ->line('Congratulations! You’ve unlocked the "' . $this->badge->name . '" badge.')
                    ->action('View Badges', url('/profile'))  // Update URL to your actual profile page
                    ->line('Keep up the great work!');
    }

    /**
     * Get the array representation of the notification for storing in the database.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'badge_name' => $this->badge->name,
            'message' => 'Congratulations! You have unlocked the ' . $this->badge->name . ' badge.',
            'icon' => $this->badge->icon,
        ];
    }
}
