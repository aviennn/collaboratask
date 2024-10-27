<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF; // Add this for PDF generation
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // Display reports options and summary
    public function index($teamId = null)
    {
        if ($teamId) {
            // Team-specific tasks
            $team = Team::findOrFail($teamId);
    
            $notStarted = $team->tasks()->where('status', 'not started')->count();
            $inProgress = $team->tasks()->where('status', 'in progress')->count();
            $done = $team->tasks()->where('status', 'done')->count();
            
            $lowPriority = $team->tasks()->where('priority', 'low')->count();
            $mediumPriority = $team->tasks()->where('priority', 'medium')->count();
            $highPriority = $team->tasks()->where('priority', 'high')->count();
    
            $overdue = $team->tasks()->where('due_date', '<', now())->where('status', '!=', 'done')->count();
            $dueThisWeek = $team->tasks()->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])->count();
            $dueToday = $team->tasks()->whereDate('due_date', now())->count(); // Apply the filter on the query, not on a collection
    
            return view('reports.team', compact(
                'team', 'notStarted', 'inProgress', 'done',
                'lowPriority', 'mediumPriority', 'highPriority', 
                'overdue', 'dueThisWeek', 'dueToday'
            ));
        } else {
            // Admin-level report for all tasks
            $notStarted = Task::where('status', 'not started')->count();
            $inProgress = Task::where('status', 'in progress')->count();
            $done = Task::where('status', 'done')->count();
    
            $lowPriority = Task::where('priority', 'low')->count();
            $mediumPriority = Task::where('priority', 'medium')->count();
            $highPriority = Task::where('priority', 'high')->count();
    
            $overdue = Task::where('due_date', '<', now())->where('status', '!=', 'done')->count();
            $dueThisWeek = Task::whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])->count();
            $dueToday = Task::whereDate('due_date', now())->count();  // Same fix here
    
            return view('reports.index', compact(
                'notStarted', 'inProgress', 'done', 
                'lowPriority', 'mediumPriority', 'highPriority',
                'overdue', 'dueThisWeek', 'dueToday'
            ));
        }
    }
    

    // Generate report based on a date range
    public function generateReport(Request $request)
    {
        // Validate the incoming date range
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
    
        // Fetch tasks between the selected dates
        $tasks = Task::whereBetween('due_date', [$request->start_date, $request->end_date])->get();
    
        // Count tasks based on status
        $notStarted = $tasks->where('status', 'not started')->count();
        $inProgress = $tasks->where('status', 'in progress')->count();
        $done = $tasks->where('status', 'done')->count();
    
        // Count tasks based on priority
        $lowPriority = $tasks->where('priority', 'low')->count();
        $mediumPriority = $tasks->where('priority', 'medium')->count();
        $highPriority = $tasks->where('priority', 'high')->count();
    
        // Count tasks based on due dates
        $overdue = $tasks->where('due_date', '<', now())->count();
        $dueThisWeek = $tasks->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $dueToday = $tasks->where('due_date', now()->toDateString())->count();
    
        // Return data as JSON
        return response()->json([
            'notStarted' => $notStarted,
            'inProgress' => $inProgress,
            'done' => $done,
            'lowPriority' => $lowPriority,
            'mediumPriority' => $mediumPriority,
            'highPriority' => $highPriority,
            'overdue' => $overdue,
            'dueThisWeek' => $dueThisWeek,
            'dueToday' => $dueToday,
        ]);
    }
    

    // Fetch team performance for reports
    public function teamPerformance()
    {
        $teams = Team::withCount(['tasks' => function ($query) {
            $query->where('status', 'done');
        }])->get();

        return view('reports.team', compact('teams'));
    }

    public function generateTeamReport(Request $request, $teamId)
{
    // Validate the date range only if the dates are provided
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
    ]);

    $team = Team::findOrFail($teamId);

    // If no date filters are applied, use the full task list
    if ($request->start_date && $request->end_date) {
        // Filter tasks by date range if provided
        $tasks = $team->tasks()->whereBetween('due_date', [$request->start_date, $request->end_date])->get();
    } else {
        // Use all tasks for the team if no dates are provided
        $tasks = $team->tasks;
    }

    // Calculate task statistics
    $notStarted = $tasks->where('status', 'not started')->count();
    $inProgress = $tasks->where('status', 'in progress')->count();
    $done = $tasks->where('status', 'done')->count();
    $lowPriority = $tasks->where('priority', 'low')->count();
    $mediumPriority = $tasks->where('priority', 'medium')->count();
    $highPriority = $tasks->where('priority', 'high')->count();
    $overdue = $tasks->where('due_date', '<', now())->count();
    $dueThisWeek = $tasks->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])->count();
    $dueToday = $tasks->where('due_date', now()->toDateString())->count();

    // Return task statistics as JSON
    return response()->json([
        'notStarted' => $notStarted,
        'inProgress' => $inProgress,
        'done' => $done,
        'lowPriority' => $lowPriority,
        'mediumPriority' => $mediumPriority,
        'highPriority' => $highPriority,
        'overdue' => $overdue,
        'dueThisWeek' => $dueThisWeek,
        'dueToday' => $dueToday,
    ]);
}

public function generatePDF($teamId)
{
    $team = Team::findOrFail($teamId);
    $pdf = new \TCPDF();

    // Task Statistics
    $notStarted = $team->tasks()->where('status', 'not started')->count();
    $inProgress = $team->tasks()->where('status', 'in progress')->count();
    $done = $team->tasks()->where('status', 'done')->count();
    
    $lowPriority = $team->tasks()->where('priority', 'low')->count();
    $mediumPriority = $team->tasks()->where('priority', 'medium')->count();
    $highPriority = $team->tasks()->where('priority', 'high')->count();
    
    $overdue = $team->tasks()->where('due_date', '<', now())->where('status', '!=', 'done')->count();
    $dueThisWeek = $team->tasks()->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])->count();
    $dueToday = $team->tasks()->whereDate('due_date', now())->count();

    // Add the title and some general info about the team
    $pdf->AddPage();
    $pdf->SetFont('Helvetica', '', 14);
    $pdf->Write(0, "Team Report: {$team->name}\n", '', 0, 'L', true, 0, false, false, 0);
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->Write(0, "Generated on: " . now()->toFormattedDateString(), '', 0, 'L', true, 0, false, false, 0);

    // Task Status Section
    $pdf->Ln(5); // Line break
    $pdf->SetFont('Helvetica', 'B', 12);
    $pdf->Write(0, "Task Status Summary", '', 0, 'L', true, 0, false, false, 0);
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->Write(0, "Not Started: {$notStarted}\n", '', 0, 'L', true, 0, false, false, 0);
    $pdf->Write(0, "In Progress: {$inProgress}\n", '', 0, 'L', true, 0, false, false, 0);
    $pdf->Write(0, "Done: {$done}\n", '', 0, 'L', true, 0, false, false, 0);

    // Task Priority Section
    $pdf->Ln(5); // Line break
    $pdf->SetFont('Helvetica', 'B', 12);
    $pdf->Write(0, "Task Priority Summary", '', 0, 'L', true, 0, false, false, 0);
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->Write(0, "Low Priority: {$lowPriority}\n", '', 0, 'L', true, 0, false, false, 0);
    $pdf->Write(0, "Medium Priority: {$mediumPriority}\n", '', 0, 'L', true, 0, false, false, 0);
    $pdf->Write(0, "High Priority: {$highPriority}\n", '', 0, 'L', true, 0, false, false, 0);

    // Due Date Section
    $pdf->Ln(5); // Line break
    $pdf->SetFont('Helvetica', 'B', 12);
    $pdf->Write(0, "Task Due Date Summary", '', 0, 'L', true, 0, false, false, 0);
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->Write(0, "Overdue: {$overdue}\n", '', 0, 'L', true, 0, false, false, 0);
    $pdf->Write(0, "Due This Week: {$dueThisWeek}\n", '', 0, 'L', true, 0, false, false, 0);
    $pdf->Write(0, "Due Today: {$dueToday}\n", '', 0, 'L', true, 0, false, false, 0);

    // Add Chart Images with Descriptions
    $pdf->Ln(5); // Line break
    $pdf->SetFont('Helvetica', 'B', 12);
    $pdf->Write(0, "Task Status Chart", '', 0, 'L', true, 0, false, false, 0);

    $taskStatusChart = document.getElementById('taskStatusChart');
    if ($taskStatusChart) {
        $pdf->Image($taskStatusChart->toDataURL('image/png'), 15, 100, 180, 90, 'PNG');
    }

    $pdf->Ln(5); // Line break
    $pdf->SetFont('Helvetica', 'B', 12);
    $pdf->Write(0, "Task Priority Chart", '', 0, 'L', true, 0, false, false, 0);

    $taskPriorityChart = document.getElementById('taskPriorityChart');
    if ($taskPriorityChart) {
        $pdf->Image($taskPriorityChart->toDataURL('image/png'), 15, 100, 180, 90, 'PNG');
    }

    $pdf->Ln(5); // Line break
    $pdf->SetFont('Helvetica', 'B', 12);
    $pdf->Write(0, "Task Due Date Chart", '', 0, 'L', true, 0, false, false, 0);

    $taskDueDateChart = document.getElementById('taskDueDateChart');
    if ($taskDueDateChart) {
        $pdf->Image($taskDueDateChart->toDataURL('image/png'), 15, 100, 180, 90, 'PNG');
    }

    return $pdf->Output("team-report-{$team->name}.pdf", 'D');
}




}
