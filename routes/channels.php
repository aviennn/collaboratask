<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('team.{teamId}', function ($user, $teamId) {
    \Log::info('Broadcast auth check', ['user' => $user->id, 'team' => $teamId]);
    return $user->teams->contains($teamId);
});

// Channel for user-specific notifications
Broadcast::channel('App.Models.User.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;  // Allow users to listen only to their own notifications
});