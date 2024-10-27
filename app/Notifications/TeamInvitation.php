<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Team;
use App\Models\User;

class TeamInvitation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $team;
    protected $inviter;

    public function __construct(Team $team, User $inviter)
    {
        $this->team = $team;
        $this->inviter = $inviter;
    }

    // Define notification delivery methods (via email and database)
    public function via($notifiable)
    {
        return ['mail', 'database']; // Send via both mail and database
    }

    // Email content
    public function toMail($notifiable)
    {
        
        return (new MailMessage)
            ->subject('You are invited to join the team: ' . $this->team->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have been invited to join the team "' . $this->team->name . '" by ' . $this->inviter->name . '.')
            //using route helper to generate correct URL
            ->action('View Invitation', route('invitations.index'))
            ->line('Thank you for using our application!');
    }

    // Database content
    public function toArray($notifiable)
    {
        return [
            'message' => 'You have been invited to join the team: ' . $this->team->name,
            'team_id' => $this->team->id,
            'inviter_name' => $this->inviter->name,
        ];
    }
}
