<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\GoogleLoginController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TeamInvitationController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\TitleController;
use App\Http\Controllers\FeedbackController;

// Google Login Routes
Route::get('auth/google', [GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');

// Middleware for Authenticated and Verified Users
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Welcome Route
Route::get('/', function () {
    return view('welcome');
});

//feedback
Route::middleware('admin')->group(function () {
    Route::get('admin/feedbacks', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('admin/tasks/{task}/feedback', [FeedbackController::class, 'show'])->name('feedback.show');
    Route::delete('/feedback/{id}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');
});

// Routes for General Feedback
Route::get('feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
Route::post('feedback/store', [FeedbackController::class, 'store'])->name('feedback.store');


Route::get('/profile/titles', [TitleController::class, 'index'])->name('titles.index');
Route::post('/profile/titles/select', [TitleController::class, 'select'])->name('titles.select');
Route::post('/profile/titles/select', [ProfileController::class, 'selectTitle'])->name('titles.select');


Route::post('/checklist-item/store', [WidgetController::class, 'storeChecklistItem']);
Route::put('/checklist-item/update/{id}', [WidgetController::class, 'updateChecklistItem']);
Route::delete('/checklist-item/delete/{id}', [WidgetController::class, 'deleteChecklistItem']);


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [WidgetController::class, 'index'])->name('dashboard');
    Route::post('/widgets', [WidgetController::class, 'store'])->name('widget.store');
    Route::post('/widgets/{id}', [WidgetController::class, 'update'])->name('widget.update');
    Route::delete('/widgets/{id}', [WidgetController::class, 'destroy'])->name('widget.destroy');
    Route::post('/widgets/{id}/notes', [WidgetController::class, 'storeNote']);
    Route::post('/widget/store', [WidgetController::class, 'store'])->name('widget.store');

});


Route::middleware(['auth'])->group(function () {
    // Route to store a note for a specific widget
    Route::post('/widgets/{id}/notes', [NoteController::class, 'store']);

    // Route to delete a note
    Route::delete('/notes/{id}', [NoteController::class, 'destroy']);
    
});


Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/generate', [ReportController::class, 'generateReport'])->name('generate');
    Route::get('/team-performance', [ReportController::class, 'teamPerformance'])->name('team');
    Route::get('/reports/team/{teamId}', [ReportController::class, 'index'])->name('reports.team');
    Route::get('/reports/{id}/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/{teamId}/generate', [ReportController::class, 'generateTeamReport'])->name('reports.team.generate');
    Route::get('/reports/{teamId}/pdf', [ReportController::class, 'generatePDF'])->name('reports.pdf');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');  // Admin report
Route::get('/reports/team/{teamId}', [ReportController::class, 'index'])->name('reports.team');  // Team report
// Example Laravel Route
Route::get('/reports/{team}/generate', [ReportController::class, 'generate']);


});

// Dashboard Layout Routes
Route::post('/admin/save-dashboard-layout', [UserController::class, 'saveDashboardLayout'])->name('admin.saveDashboardLayout');
Route::post('/user/save-dashboard-layout', [UserController::class, 'saveDashboardLayout'])->name('user.saveDashboardLayout');
Route::post('/user/reset-dashboard-layout', [UserController::class, 'resetDashboardLayout'])->name('user.resetDashboardLayout');
Route::post('/profile/update-border', [ProfileController::class, 'updateBorder'])->name('profile.update-border');

// Notification Routes
Route::get('/notifications/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
Route::get('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
Route::delete('/notifications/{id}/delete', [NotificationController::class, 'delete'])->name('notifications.delete');
Route::delete('/notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');
Route::delete('/notifications/delete-all-chat', [NotificationController::class, 'deleteAllChat'])->name('notifications.deleteAllChat');
Route::delete('/notifications/delete-selected', [NotificationController::class, 'deleteSelected'])->name('notifications.deleteSelected');
Route::get('/notifications/mark-all-chat-read', [NotificationController::class, 'markAllChatAsRead'])->name('notifications.markAllChatAsRead');
Route::get('/notifications/mark-all-task-read', [NotificationController::class, 'markAllTaskAsRead'])->name('notifications.markAllTaskAsRead');
Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
Route::delete('/notifications/delete-all-task', [NotificationController::class, 'deleteAllTask'])->name('notifications.deleteAllTask');
Route::delete('/notifications/delete/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');

Route::get('/download/{filename}', [FileController::class, 'download'])->name('file.download'); 


// Team Invitation Routes
Route::post('/teams/invitations/{id}/accept', [TeamInvitationController::class, 'accept'])->name('invitations.accept');
Route::post('/teams/invitations/{id}/reject', [TeamInvitationController::class, 'reject'])->name('invitations.reject');

// User Dashboard Route
Route::get('/dashboard', [HomeController::class, 'userDashboard'])->middleware(['auth', 'verified'])->name('dashboard');

// Auth Middleware Group for Various Routes
Route::middleware('auth')->group(function () {
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy')->middleware('auth');

    Route::get('/tasks/fetchTasksByPriorityStatus', [TaskController::class, 'fetchTasksByPriorityStatus']);
    Route::post('/tasks/{task}/update-category', [TaskController::class, 'updateTaskCategory']);

    // Invitations
    Route::get('/teams/invitations', [TeamInvitationController::class, 'index'])->name('invitations.index');

    // Calendar and Tasks
    Route::get('/calendar', [TaskController::class, 'calendarView'])->name('calendar.view');
    Route::post('/tasks/calendar/store', [TaskController::class, 'storeFromCalendar'])->name('tasks.calendar.store');
    Route::post('/tasks/{id}/update-due-date', [TaskController::class, 'updateDueDate']);
    Route::get('/tasks/events', [TaskController::class, 'fetchEvents'])->name('tasks.events');

    // User Settings
    Route::get('/user/settings', [UserController::class, 'settings'])->name('user.settings');

    // Font Family Update
    Route::post('/settings/update', [UserController::class, 'updateSettings'])->name('user.updateSettings');
    Route::post('/settings/reset', [UserController::class, 'resetTheme'])->name('user.resetTheme');


    // Task Routes
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('user.tasks.create');
Route::post('/tasks/{task}/grade', [TaskController::class, 'grade'])->name('user.tasks.grade');
Route::put('/tasks/{id}/grade', [TaskController::class, 'grade'])->name('tasks.grade');
Route::post('/tasks', [TaskController::class, 'store'])->name('user.tasks.store');
    Route::get('/tasks', [TaskController::class, 'index'])->name('user.tasks.index');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('user.tasks.show');
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('user.tasks.edit');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('user.tasks.update');
    Route::patch('/tasks/{id}/status', [TaskController::class, 'updateStatus'])->name('user.tasks.updateStatus');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('user.tasks.destroy');

    Route::delete('/tasks/{id}/attachments/{attachmentId}', [TaskController::class, 'removeAttachment'])->name('user.tasks.removeAttachment');
    Route::get('/tasks/{id}/attachments/{attachmentId}', [TaskController::class, 'downloadAttachment'])->name('user.tasks.downloadAttachment');
    Route::post('/teams/{team}/tasks', [TaskController::class, 'storeForTeam'])->name('team.tasks.store');
    Route::get('/teams/{team}/messages', [MessageController::class, 'getMessages'])->name('team.messages');


    // Checklist Routes for Tasks
    Route::post('/tasks/{task}/checklists', [TaskController::class, 'addChecklistItem'])->name('user.tasks.addChecklistItem');
    Route::put('/tasks/{task}/checklists/{checklist}', [TaskController::class, 'updateChecklistItem'])->name('user.tasks.updateChecklistItem');
    Route::delete('/tasks/{task}/checklists/{checklist}', [TaskController::class, 'deleteChecklistItem'])->name('user.tasks.deleteChecklistItem');

    // Task Approval or Rejection
    Route::put('tasks/{task}/approve-or-reject', [TaskController::class, 'approveOrReject'])->name('user.tasks.approveOrReject');

    // Team Routes
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
        Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
        Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
        Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
        Route::get('/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
        Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
        Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
        
        

        // Team Members Management
        Route::post('/teams/{team}/add-member', [TeamController::class, 'addMember'])->name('teams.addMember');
        Route::delete('/teams/{team}/remove-member/{user}', [TeamController::class, 'removeMember'])->name('teams.removeMember');
    });

    // Messaging Routes
    Route::get('/teams/{team}/messages/{message?}', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/teams/{team}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages', [MessageController::class, 'allMessages'])->name('messages.all');
    Route::post('/teams/{team}/messages', [MessageController::class, 'store'])->name('teams.messages.store');


    // Rewards System
    Route::prefix('rewards')->group(function () {
        Route::post('/', [RewardController::class, 'store'])->name('rewards.store');
        Route::put('/{id}', [RewardController::class, 'update'])->name('rewards.update');
        Route::delete('/{id}', [RewardController::class, 'destroy'])->name('rewards.destroy');
        Route::post('/redeem/{id}', [RewardController::class, 'redeem'])->name('rewards.redeem');
    });

    Route::get('/reports/{teamId}/generate', [ReportController::class, 'generateTeamReport'])->name('reports.team.generate');
    Route::get('/reports/{teamId}/pdf', [ReportController::class, 'generatePDF'])->name('reports.pdf');
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change_password');    
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Analytics Route (this was missing)
    Route::get('/teams/{team_id}/user/{user_id}/analytics', [TeamController::class, 'viewAnalytics'])->name('user.analytics');
});

require __DIR__.'/auth.php';

// Admin-specific Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
    Route::delete('/admin/tasks/{id}', [TaskController::class, 'destroy'])->name('admin.tasks.destroy');
    Route::resource('admin/tasks', TaskController::class);

    // User management routes for admin
    Route::get('admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('admin/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::get('admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('admin.users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::resource('admin/teams', TeamController::class);
});
