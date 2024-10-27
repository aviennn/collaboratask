<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\SessionDuration;
use Carbon\Carbon;

class LogSessionEnd
{
    public function handle(Logout $event)
    {
        // Log the session end time when the user logs out
        $user = $event->user;

        // Find the latest session where the logout_time is still null
        $session = SessionDuration::where('user_id', $user->id)
                                  ->whereNull('logout_time')
                                  ->latest('login_time')
                                  ->first();

        if ($session) {
            // Update the session with logout_time and duration
            $logoutTime = Carbon::now();
            $session->update([
                'logout_time' => $logoutTime,
                'duration_in_minutes' => $logoutTime->diffInMinutes($session->login_time),
            ]);
        }
    }
}
