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
    // Determine the avatar path or fallback to avatar5.png
    $avatarPath = $this->message->user->profile_photo_path 
        ? asset('storage/' . $this->message->user->profile_photo_path) 
        : asset('dist/img/avatar5.png');

    return new BroadcastMessage([
        'message_id' => $this->message->id,
        'message_body' => $this->message->message,
        'sender_name' => $this->message->user->name,
        'team_id' => $this->message->team_id,
        'team_name' => $this->message->team->name,
        'sender_avatar' => $avatarPath, // Add avatar path for broadcast notifications
    ]);
}

public function toArray($notifiable)
{
    $avatarPath = $this->message->user->profile_photo_path 
        ? asset('storage/' . $this->message->user->profile_photo_path)
        : asset('dist/img/avatar5.png');

    return [
        'message_id' => $this->message->id,
        'message_body' => $this->message->message,
        'sender_name' => $this->message->user->name,
        'team_id' => $this->message->team_id,
        'team_name' => $this->message->team->name,
        'sender_avatar' => $avatarPath,
    ];
}

}