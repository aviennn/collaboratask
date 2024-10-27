<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;
use App\Models\Team;
use App\Models\Widget; 
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
{
    // Admin Dashboard logic
    $totalTasks = Task::count();
    $totalUsers = User::count();
    $totalTeams = Team::count();  // Total number of teams

    // Fetch tasks with deadlines this week
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();
    $tasks = Task::whereBetween('due_date', [$startOfWeek, $endOfWeek])->get();

    // Fetch recent individual tasks (excluding tasks associated with a team)
    $recentIndividualTasks = Task::whereNull('team_id')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->with('user')
        ->get();

    // Fetch recent team tasks (only tasks associated with a team)
    $recentTeamTasks = Task::whereNotNull('team_id')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->with('team', 'user')
        ->get();

    // Task progress and priority stats
    $taskData = Task::select('status', \DB::raw('count(*) as total'))
        ->groupBy('status')
        ->pluck('total', 'status')
        ->all();
    $taskPriorities = [
        'high' => Task::where('priority', 'high')->count(),
        'medium' => Task::where('priority', 'medium')->count(),
        'low' => Task::where('priority', 'low')->count(),
    ];
    $taskStatusCounts = [
        'all' => $totalTasks,
        'not started' => $taskData['not started'] ?? 0,
        'in progress' => $taskData['in progress'] ?? 0,
        'done' => $taskData['done'] ?? 0,
    ];

    $user = Auth::user();  // Get the authenticated user
    $dashboardOrder = $user->dashboard_layout ? json_decode($user->dashboard_layout, true) : null;
    return view('admin.dashboard', compact(
        'totalTasks', 
        'totalUsers', 
        'totalTeams',  
        'tasks', 
        'taskData', 
        'taskStatusCounts', 
        'taskPriorities', 
        'recentIndividualTasks',  
        'recentTeamTasks',
        'dashboardOrder'
    ));
}

public function userDashboard()
{
    $user = Auth::user(); // Get the currently authenticated user
    $totalTasks = Task::where('user_id', $user->id)->count();
    $userTeamsCount = $user->teams()->count();

    // Fetch the teams the user is part of
    $userTeams = $user->teams;

    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();
    $tasks = Task::where('user_id', $user->id)
        ->whereBetween('due_date', [$startOfWeek, $endOfWeek])
        ->get();

    $recentIndividualTasks = Task::where('user_id', $user->id)
        ->whereNull('team_id')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    $recentTeamTasks = Task::whereHas('team', function ($query) use ($user) {
        $query->whereHas('members', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });
    })
    ->where('user_id', $user->id)
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->with('team')
    ->get();

    $taskData = Task::select('status', \DB::raw('count(*) as total'))
        ->where('user_id', $user->id)
        ->groupBy('status')
        ->pluck('total', 'status')
        ->all();

    $taskPriorities = [
        'high' => Task::where('user_id', $user->id)->where('priority', 'high')->count(),
        'medium' => Task::where('user_id', $user->id)->where('priority', 'medium')->count(),
        'low' => Task::where('user_id', $user->id)->where('priority', 'low')->count(),
    ];

    $taskStatusCounts = [
        'all' => $totalTasks,
        'not started' => $taskData['not started'] ?? 0,
        'in progress' => $taskData['in progress'] ?? 0,
        'done' => $taskData['done'] ?? 0,
    ];

    $latestSession = \App\Models\SessionDuration::where('user_id', $user->id)
        ->latest('login_time')
        ->first();

    // Fetch the saved dashboard layout if it exists
    $dashboardOrder = $user->dashboard_layout ? json_decode($user->dashboard_layout, true) : null;

    // Fetch user notifications
    $notifications = $user->notifications;
    $unreadNotifications = $user->unreadNotifications;

    // **Fetch user widgets (including Notes)**
    $widgets = Widget::where('user_id', $user->id)->with('notes')->get(); // Fetch widgets for the user

    
    return view('dashboard', compact(
        'user',
        'totalTasks', 
        'tasks', 
        'taskData', 
        'taskStatusCounts', 
        'taskPriorities', 
        'recentIndividualTasks',  
        'recentTeamTasks',
        'userTeams', 
        'userTeamsCount',
        'dashboardOrder', // Pass the saved layout order to the view
        'notifications',  // Pass all notifications to the view
        'unreadNotifications',  // Pass unread notifications to the view
        'latestSession',  // Pass the latest session data to the view
        'widgets'  // **Pass widgets to the view**
    ));
}

}