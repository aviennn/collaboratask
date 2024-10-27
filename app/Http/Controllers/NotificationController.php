<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Mark a specific notification as read
    public function markAsRead(Request $request, $id)
    {
        // Find the notification
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        // Get the redirect URL from the request or go back to the previous page
        $redirectUrl = $request->query('redirect_to', url()->previous());

        return redirect($redirectUrl);
    }

    // Mark all notifications (both chat and task) as read
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('status', 'All notifications marked as read.');
    }

    // Mark all chat notifications as read
    // Mark all chat notifications as read
public function markAllChatAsRead()
{
    // Update the 'read_at' column for unread chat notifications
    Auth::user()->unreadNotifications()
        ->where('type', 'App\Notifications\NewMessageNotification')
        ->update(['read_at' => now()]);

    return response()->json(['status' => 'success']);
}


    

    // Mark all task notifications as read
   // NotificationController.php

   public function markAllTaskAsRead()
{
    Auth::user()->unreadNotifications()
        ->whereIn('type', [
            'App\Notifications\TaskAssigned',
            'App\Notifications\TaskDueReminderNotification',
            'App\Notifications\TaskOverdueNotification',
            'App\Notifications\TaskProgressUpdated'
        ])
        ->update(['read_at' => now()]); // This should mark notifications as read in the DB

    // Return the updated count
    $unreadTaskCount = Auth::user()->unreadNotifications()
        ->whereIn('type', [
            'App\Notifications\TaskAssigned',
            'App\Notifications\TaskDueReminderNotification',
            'App\Notifications\TaskOverdueNotification',
            'App\Notifications\TaskProgressUpdated'
        ])
        ->count();

    return response()->json(['unreadTaskCount' => $unreadTaskCount], 200);
}


   

    // Delete a specific notification
    public function delete($id)
    {
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            $notification->delete();
        }

        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }

    // Delete all notifications
    public function deleteAll()
    {
        Auth::user()->notifications()->delete();

        return redirect()->back()->with('success', 'All notifications deleted successfully.');
    }

    // Delete all chat notifications
    public function deleteAllChat()
    {
        Auth::user()->notifications()
            ->where('type', 'App\Notifications\NewMessageNotification') // Adjust the type to match your chat notification
            ->delete();

        return redirect()->back()->with('success', 'All chat notifications deleted successfully.');
    }

    // Delete all task notifications
    public function deleteAllTask()
    {
        // Fetch task notifications to delete
        $taskNotifications = Auth::user()->notifications()
            ->whereIn('type', [
                'App\Notifications\TaskAssigned',
                'App\Notifications\TaskDueReminderNotification',
                'App\Notifications\TaskOverdueNotification',
                'App\Notifications\TaskProgressUpdated'
            ]);
    
        // Check if any task notifications are found
        if ($taskNotifications->count() === 0) {
            
        }
    
        // Proceed to delete if notifications are found
        $taskNotifications->delete();
        
        return redirect()->back()->with('success', 'All task notifications deleted.');
    }
    
    

    // Delete selected notifications
    public function deleteSelected(Request $request)
    {
        $notificationIds = $request->input('selected_notifications', []);

        if (!empty($notificationIds)) {
            Auth::user()->notifications()->whereIn('id', $notificationIds)->delete();
        }

        return redirect()->back()->with('success', 'Selected notifications deleted successfully.');
    }
}
