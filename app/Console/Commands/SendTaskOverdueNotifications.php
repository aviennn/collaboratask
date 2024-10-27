<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;

class SendTaskOverdueNotifications extends Command
{
    // Define the command signature and description
    protected $signature = 'tasks:send-overdue-notifications';
    protected $description = 'Send notifications for tasks that are overdue.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Call the Task model method to send overdue notifications
        Task::sendOverdueNotifications();

        $this->info('Overdue task notifications have been sent successfully.');

        return 0;
    }
}
