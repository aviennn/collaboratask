<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\SessionDuration;
use Carbon\Carbon;

class LogSessionStart
{
    public function handle(Login $event)
    {
        // Log the session start time when the user logs in
        $user = $event->user;

        // Create a new session entry in the database
        SessionDuration::create([
            'user_id' => $user->id,
            'login_time' => Carbon::now(),
            'logout_time' => null,
            'duration_in_minutes' => null,
        ]);
    }
}
