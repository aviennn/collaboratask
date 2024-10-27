<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RewardRedeemedNotification extends Notification
{
    use Queueable;

    public $reward;
    public $team;
    public $user;

    public function __construct($reward, $team, $user)
    {
        $this->reward = $reward;
        $this->team = $team;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database'];  // You can add 'mail' here if you want to send an email
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->user->name . ' has redeemed the reward: ' . $this->reward->name,
            'team_name' => $this->team->name,
            'reward_name' => $this->reward->name,
        ];
    }
}
