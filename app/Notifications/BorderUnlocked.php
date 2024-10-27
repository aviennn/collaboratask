<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BorderUnlocked extends Notification
{
    use Queueable;

    protected $border;

    /**
     * Create a new notification instance.
     *
     * @param $border
     */
    public function __construct($border)
    {
        $this->border = $border;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
                    ->subject('You’ve unlocked a new border!')
                    ->line('Awesome! You’ve unlocked the "' . $this->border->name . '" profile border.')
                    ->action('View Borders', url('/profile'))  // Update URL to your actual profile page
                    ->line('Customize your profile with this new look!');
    }
}
