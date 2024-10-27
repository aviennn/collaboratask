<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;
use App\Models\Border;

class RewardService
{
    /**
     * Check if the user qualifies for any badges or borders after completing a task.
     */
    public function handleTaskCompletionRewards(User $user)
    {
        \Log::info('Reward service triggered for user: ' . $user->id);

        $taskCount = $user->tasks()->where('status', 'done')->count();
        \Log::info('User ' . $user->id . ' completed task count: ' . $taskCount);

        // Iterate through badges from the config and check if the user qualifies for any
        foreach (config('badges.badges') as $badgeConfig) {
            if ($taskCount >= $badgeConfig['tasks_required'] && !$user->badges->contains('name', $badgeConfig['name'])) {
                \Log::info('Assigning ' . $badgeConfig['name'] . ' to user: ' . $user->id);
                $this->assignBadge($user, $badgeConfig['name']);
            }
        }

        // Handle border rewards (as before)
        $this->handleBorderRewards($user, $taskCount);
    }

    /**
     * Assign a badge to the user.
     */
    private function assignBadge(User $user, $badgeName)
    {
        $badge = Badge::where('name', $badgeName)->first();
        if ($badge) {
            // Check if the badge already exists in the user_badges table
            if (!$user->badges->contains('name', $badge->name)) {
                \Log::info('Assigning badge ' . $badge->name . ' to user ' . $user->id);
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
                // Notify the user
                $user->notify(new \App\Notifications\BadgeUnlocked($badge));
            }
        } else {
            \Log::error('Badge not found: ' . $badgeName);
        }
    }

    /**
     * Assign borders based on task completion count.
     */
    private function handleBorderRewards(User $user, $taskCount)
    {
        if ($taskCount >= 4 && !$user->borders()->where('name', 'Gold Border')->exists()) {
            \Log::info('Assigning Gold Border to user: ' . $user->id);
            $this->assignBorder($user, 'Gold Border');
        }

        if ($taskCount >= 200 && !$user->borders()->where('name', 'Platinum Border')->exists()) {
            \Log::info('Assigning Platinum Border to user: ' . $user->id);
            $this->assignBorder($user, 'Platinum Border');
        }
    }

    /**
     * Assign a border to the user.
     */
    private function assignBorder(User $user, $borderName)
    {
        $border = Border::where('name', $borderName)->first();
        if ($border) {
            $user->borders()->attach($border->id, ['is_active' => false]);
            // Optionally, notify the user of the new border
            $user->notify(new \App\Notifications\BorderUnlocked($border));
        }
    }
}
