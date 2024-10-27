<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;

class SendTaskDueReminders extends Command
{
    // Command name to call from CLI
    protected $signature = 'tasks:send-due-reminders';

    // Command description
    protected $description = 'Send reminders for tasks that are due in 1 week, 3 days, or tomorrow';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Call the method to send due date reminders
        Task::sendDueDateReminder();

        // Return success status
        $this->info('Task due date reminders have been sent.');
    }
}
