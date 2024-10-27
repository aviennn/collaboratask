<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\Message;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    // Send notification via broadcast (Pusher) and store in the database
    public function via($notifiable)
    {
        return ['broadcast', 'database']; // Ensure the notification is also stored in the database
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message_id' => $this->message->id,
            'message_body' => $this->message->message,
            'sender_name' => $this->message->user->name,
            'team_id' => $this->message->team_id,
            'team_name' => $this->message->team->name,
        ]);
    }

    public function toArray($notifiable)
    {
        // Data stored in the `data` column of the `notifications` table
        return [
            'message_id' => $this->message->id,
            'message_body' => $this->message->message,
            'sender_name' => $this->message->user->name,
            'team_id' => $this->message->team_id,
            'team_name' => $this->message->team->name,
            'sender_avatar' => $this->message->user->avatar ?? asset('default-avatar.png'), // Include avatar URL   
        ];
    }
}
