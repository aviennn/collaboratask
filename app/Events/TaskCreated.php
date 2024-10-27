<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Illuminate\Queue\SerializesModels;
use App\Models\Task;

class TaskCreated implements ShouldBroadcast
{   
    use SerializesModels;

    public $task;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Task $task
     */
    public function __construct(Task $task)     
    {
        $this->task = $task;  // Pass the task instance to the event
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Broadcast on the 'tasks' public channel
        return new Channel('tasks');
    }

    /**
     * Data to broadcast with the event.
     *
     * @return array
     */
    public function broadcastWith()
    {
        // Customize the data sent to the frontend
        return [
            'task' => [
                'id' => $this->task->id,
                'name' => $this->task->name,
                'priority' => $this->task->priority,
                'due_date' => $this->task->due_date->toDateString(),
                'status' => $this->task->status,
                'description' => $this->task->description,
            ],
        ];
    }
}
