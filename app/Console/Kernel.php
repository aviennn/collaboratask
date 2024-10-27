<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Task; // Assuming you have a Task model
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\SendTaskOverdueNotifications::class,
        // You can list any other custom commands here as well.
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Schedule the task: send task due date reminders every day at midnight
        $schedule->command('tasks:send-due-reminders')->dailyAt('00:00');

        // Schedule the overdue task notification to run daily
        $schedule->command('tasks:send-overdue-notifications')->dailyAt('00:00');
    }

    /**
     * Custom function to handle sending due date reminders.
     */
    protected function sendDueDateReminders()
    {
        // Get the current date
        $today = Carbon::today();
    
        // Get all tasks that are due in 1 week, 3 days, and tomorrow
        $tasks = Task::whereIn('due_date', [
            $today->copy()->addWeek()->toDateString(),  // Due in 1 week
            $today->copy()->addDays(3)->toDateString(),  // Due in 3 days
            $today->copy()->addDay()->toDateString()     // Due tomorrow
        ])->get();
    
        foreach ($tasks as $task) {
            // Calculate the days remaining until the task's due date
            $daysRemaining = $today->diffInDays(Carbon::parse($task->due_date), false);
    
            // Notify the assigned users about the due date approaching
            foreach ($task->assignedUsers as $user) {
                $user->notify(new \App\Notifications\TaskDueReminderNotification($task, $daysRemaining));
            }
    
            // Log the task reminder for debugging
            Log::info("Sent due date reminder for Task: {$task->name}, due on: {$task->due_date}");
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
