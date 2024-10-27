import './bootstrap'; // Ensure bootstrap is loaded
import Alpine from 'alpinejs';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';  // Use import instead of require

// Set up Pusher with Laravel Echo
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,  // Use the Vite-specific env import syntax
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,  // Ensure you have these environment variables in your .env file
    forceTLS: true
});

// ------------------------------------
// Subscribe to the public tasks channel for real-time task updates
// ------------------------------------
window.Echo.channel('tasks')
    .listen('TaskCreated', (event) => {
        console.log("Task Created Event received:", event);

        // Dynamically add the task to the calendar
        var priorityColor = '';
        if (event.task.priority === 'low') {
            priorityColor = '#28a745'; // Green for low priority
        } else if (event.task.priority === 'medium') {
            priorityColor = '#ffc107'; // Yellow for medium priority
        } else if (event.task.priority === 'high') {
            priorityColor = '#dc3545'; // Red for high priority
        }

        // Assuming you're using FullCalendar, add the new task to the calendar
        if (typeof calendar !== 'undefined') {
            calendar.addEvent({
                title: event.task.name,
                start: event.task.due_date,
                allDay: true,
                backgroundColor: priorityColor,
                borderColor: priorityColor,
                extendedProps: {
                    priority: event.task.priority,
                    status: event.task.status,
                    due_date: event.task.due_date,
                }
            });
        } else {
            console.error("Calendar instance not found!");
        }
    });

    
// Subscribe to the team channel for real-time messages, if teamId is defined
if (typeof teamId !== 'undefined' && teamId !== null) {
    console.log("Subscribing to team channel: team." + teamId);
    
    window.Echo.private('team.' + teamId)
        .listen('MessageSent', (e) => {
            console.log("New message received:", e.message);  // Log the received message
            // Handle the new message here (e.g., update the chat UI)
        });
} else {
    console.log("No teamId found; skipping team channel subscription.");
}

// Subscribe to team notifications, if teamId is defined
if (typeof teamId !== 'undefined' && teamId !== null) {
    console.log("Subscribing to team notifications: team." + teamId);
    
    window.Echo.private('team.' + teamId)
        .notification((notification) => {
            console.log("Notification received:", notification);  // Check if this is triggered when a message is sent
            
            if (notification.message_id) {
                console.log("Calling displayChatNotification function"); // Add this log

                displayChatNotification(notification);  // Display chat notification
            }
        });
} else {
    console.log("No teamId found; skipping team notifications subscription.");
}

// Subscribe to user-specific notifications, if userId is defined
if (typeof userId !== 'undefined' && userId !== null) {
    console.log("Subscribing to user notifications: user." + userId);
    
    window.Echo.private('App.Models.User.' + userId)
    .notification((notification) => {
        console.log("User notification received:", notification);  // Check if this is triggered when a message is sent
        
        // Ensure the notification is related to chat messages
        if (notification.message_id) {
            console.log("Calling displayChatNotification function"); // Add this log
            displayChatNotification(notification);  // Display chat notification
        }
    });
} else {
    console.log("No userId found; skipping user notifications subscription.");
}

// Function to update the chat notification dropdown
// Make sure this function is available globally by attaching it to the window object

window.displayChatNotification = function(notification) {
    console.log("Displaying notification:", notification);

    // Select the notification dropdown and badge count elements
    let notificationDropdown = document.getElementById('chat-notification-dropdown');
    let badgeCount = document.getElementById('chat-notification-count');

    // Ensure both elements exist
    if (!notificationDropdown) {
        console.error("Notification dropdown element not found.");
        return;
    }

    if (!badgeCount) {
        console.error("Badge count element not found.");
        return;
    }

    // Get the current badge count, default to 0 if NaN
    let currentCount = parseInt(badgeCount.textContent) || 0;

    // Enhance the notification with team details
    let teamName = notification.team_name || "Unknown Team";  // Ensure the team name is included

    // Create the new notification HTML structure with improved design
    let newNotification = `
        <li class="notification-item" style="border-bottom: 1px solid #f1f1f1; padding: 10px;">
            <a href="/teams/${notification.team_id}/messages/${notification.message_id}" style="text-decoration: none; color: inherit;">
                <div style="display: flex; align-items: center;">
                    <div style="flex: 0 0 50px; margin-right: 10px;">
                        <img src="/path/to/team/avatar.png" alt="Team Avatar" style="width: 50px; height: 50px; border-radius: 50%;">
                    </div>
                    <div style="flex-grow: 1;">
                        <strong style="font-size: 14px; color: #007bff;">${notification.sender_name}:</strong>
                        <span style="display: block; font-size: 12px; color: #555;">${notification.message_body}</span>
                        <span style="display: block; font-size: 11px; color: #888;">From: ${teamName}</span>
                        <small class="text-muted" style="display: block; font-size: 10px;">Just Now</small>
                    </div>
                </div>
            </a>
        </li>
    `;

    // Insert new notification into the dropdown
    notificationDropdown.innerHTML += newNotification;

    // Increment the notification badge count
    badgeCount.textContent = currentCount + 1;
};

window.Alpine = Alpine;
Alpine.start();
