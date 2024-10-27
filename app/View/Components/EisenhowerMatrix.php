<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class EisenhowerMatrix extends Component
{
    public $personalUrgentImportant;
    public $personalNotUrgentImportant;
    public $personalUrgentNotImportant;
    public $personalNotUrgentNotImportant;

    public $teamUrgentImportant;
    public $teamNotUrgentImportant;
    public $teamUrgentNotImportant;
    public $teamNotUrgentNotImportant;

    public function __construct()
    {
        $user = Auth::user();

        // Fetch personal tasks that are not completed (i.e., status is not 'done')
        $personalTasks = $user->personalTasks()->where('status', '!=', 'done')->get();

        // Fetch team tasks that are not completed (i.e., status is not 'done')
        $teamTasks = $user->teamTasks()->where('status', '!=', 'done')->get();

        // Categorize personal tasks into quadrants
        $this->personalUrgentImportant = $personalTasks->filter(fn($task) => $task->urgency == 1 && $task->importance == 1);
        $this->personalNotUrgentImportant = $personalTasks->filter(fn($task) => $task->urgency == 0 && $task->importance == 1);
        $this->personalUrgentNotImportant = $personalTasks->filter(fn($task) => $task->urgency == 1 && $task->importance == 0);
        $this->personalNotUrgentNotImportant = $personalTasks->filter(fn($task) => $task->urgency == 0 && $task->importance == 0);

        // Categorize team tasks into quadrants
        $this->teamUrgentImportant = $teamTasks->filter(fn($task) => $task->urgency == 1 && $task->importance == 1);
        $this->teamNotUrgentImportant = $teamTasks->filter(fn($task) => $task->urgency == 0 && $task->importance == 1);
        $this->teamUrgentNotImportant = $teamTasks->filter(fn($task) => $task->urgency == 1 && $task->importance == 0);
        $this->teamNotUrgentNotImportant = $teamTasks->filter(fn($task) => $task->urgency == 0 && $task->importance == 0);
    }

    public function render()
    {
        return view('components.eisenhower-matrix');
    }
}
