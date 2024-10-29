<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navigation Blade: dark mode toggle button -->
<button id="darkModeToggle" class="btn btn-secondary">
    Toggle Dark Mode
</button>
        <!-- Navbar Search -->
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
                <form class="form-inline">
                    <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search" >
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>


        <script>
            var authUserAvatar = @json(Auth::user()->profile_photo_path 
    ? asset('storage/' . Auth::user()->profile_photo_path) 
    : asset('dist/img/avatar5.png'));
        </script>

<!-- Chat Notifications Dropdown -->
@php
$messageNotifications = auth()->user()->notifications()
    ->where('type', 'App\Notifications\NewMessageNotification')
    ->get();
@endphp

<!-- Chat Notifications Dropdown -->
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-comments"></i>
        @php
            $messageNotificationsCount = $unreadNotifications->where('type', 'App\Notifications\NewMessageNotification')->count();
        @endphp
        @if($messageNotificationsCount > 0)
            <span class="badge badge-danger navbar-badge chat-notification-count">{{ $messageNotificationsCount }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right custom-dropdown">
        <span class="dropdown-item dropdown-header">{{ $messageNotifications->count() }} New Messages</span>
        <div class="notification-list custom-scroll" style="max-height: 240px; overflow-y: auto;">
            @foreach($messageNotifications as $notification)
                <div class="dropdown-divider"></div>
                <div class="d-flex align-items-center justify-content-between px-2">
                    <a href="{{ route('notifications.markAsRead', $notification->id) }}?redirect_to=/teams/{{ $notification->data['team_id'] }}/messages/{{ $notification->data['message_id'] }}" class="dropdown-item w-100 d-flex align-items-center">
                    <img 
    src="{{ $notification->data['sender_avatar'] ?? asset('dist/img/avatar5.png') }}" 
    alt="User Avatar" 
    class="rounded-circle mr-3" 
    style="width: 50px; height: 50px; object-fit: cover; align-self: center;"
    onerror="this.src='{{ asset('dist/img/avatar5.png') }}';" 
>

                        <div class="media-body">
                            <p class="mb-0 text-sm font-weight-bold">{{ $notification->data['team_name'] }}</p>
                            <p class="mb-0 text-sm {{ is_null($notification->read_at) ? 'font-weight-bold' : '' }}">
                                {{ $notification->data['sender_name'] }}: {{ $notification->data['message_body'] }}
                            </p>
                            <p class="text-muted text-sm mb-0">
                                <i class="fas fa-clock mr-1"></i> {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </a>
                    <form method="POST" action="{{ route('notifications.delete', $notification->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger ml-2">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
        <!-- Footer Actions for Chat Notifications -->
        <div class="sticky-footer p-2 text-center d-flex flex-column justify-content-center align-items-center">
            <a href="#" id="markAllChatAsRead" class="btn btn-primary btn-sm mb-2">
                <i class="fas fa-check-circle mr-2"></i> Mark All as Read
            </a>
            <form method="POST" action="{{ route('notifications.deleteAllChat') }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash-alt mr-2"></i> Delete All Notifications
                </button>
            </form>
        </div>
    </div>
</li>

@php
    // Fetch unread notifications for the authenticated user
    $unreadNotifications = auth()->user()->unreadNotifications()
        ->whereIn('type', [
            'App\Notifications\TaskAssigned',
            'App\Notifications\TaskDueReminderNotification',
            'App\Notifications\TaskOverdueNotification',
            'App\Notifications\TaskProgressUpdated',
            'App\Notifications\RewardRedeemedNotification',
            'App\Notifications\BadgeUnlocked',
            'App\Notifications\FeedbackSubmitted', 
        ])
        ->get();

    // Get all notifications (both read and unread)
    $taskNotifications = auth()->user()->notifications()
        ->whereIn('type', [
            'App\Notifications\TaskAssigned',
            'App\Notifications\TaskDueReminderNotification',
            'App\Notifications\TaskOverdueNotification',
            'App\Notifications\TaskProgressUpdated',
            'App\Notifications\RewardRedeemedNotification',
            'App\Notifications\BadgeUnlocked',
            'App\Notifications\FeedbackSubmitted', 
        ])
        ->get();

    // Count unread notifications
    $taskNotificationsCount = $unreadNotifications->count();
@endphp

<!-- Task Notifications Dropdown -->
<li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-bell"></i>
                @if($taskNotificationsCount > 0)
                    <span class="badge badge-warning navbar-badge task-notification-count">{{ $taskNotificationsCount }}</span>
                @endif
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right custom-dropdown">
                <span class="dropdown-item dropdown-header">{{ $taskNotifications->count() }} Notifications</span>
                <div class="notification-list custom-scroll" style="max-height: 240px; overflow-y: auto;">
                @foreach($taskNotifications as $notification)
    <div class="dropdown-divider"></div>
    <div class="d-flex align-items-center justify-content-between px-2">
        <a href="{{ route('notifications.markAsRead', $notification->id) }}" class="dropdown-item w-100">
            <i class="fas fa-info-circle mr-3"></i>
            <div class="media-body">
                @if ($notification->type == 'App\Notifications\BadgeUnlocked')
                    <!-- Badge Unlocked Notification -->
                    <p class="text-sm font-weight-bold">Badge Unlocked: {{ $notification->data['badge_name'] }}</p>
                    <p class="text-sm">{{ $notification->data['message'] }}</p>
                    <img src="{{ asset($notification->data['icon']) }}" alt="Badge Icon" style="width: 20px; height: 20px;">
                
                @elseif ($notification->type == 'App\Notifications\TaskAssigned')
                    <!-- Task Assigned Notification -->
                    <p class="text-sm font-weight-bold">New Task Assigned</p>
                    <p class="text-sm">{{ $notification->data['task_name'] ?? 'Task Details Unavailable' }}</p>
                    <p class="text-sm">{{ $notification->data['message'] ?? '' }}</p>

                @elseif ($notification->type == 'App\Notifications\TaskDueReminderNotification')
                    <!-- Task Due Reminder -->
                    <p class="text-sm font-weight-bold">Task Due Reminder</p>
                    <p class="text-sm">{{ $notification->data['task_name'] ?? 'Task Details Unavailable' }}</p>
                    <p class="text-sm">{{ $notification->data['message'] ?? '' }}</p>

                @elseif ($notification->type == 'App\Notifications\TaskOverdueNotification')
                    <!-- Task Overdue Notification -->
                    <p class="text-sm font-weight-bold">Task Overdue</p>
                    <p class="text-sm">{{ $notification->data['task_name'] ?? 'Task Details Unavailable' }}</p>
                    <p class="text-sm">{{ $notification->data['message'] ?? '' }}</p>

                @elseif ($notification->type == 'App\Notifications\TaskProgressUpdated')
                    <!-- Task Progress Updated -->
                    <p class="text-sm font-weight-bold">Task Progress Updated</p>
                    <p class="text-sm">{{ $notification->data['task_name'] ?? 'Task Details Unavailable' }}</p>
                    <p class="text-sm">{{ $notification->data['message'] ?? '' }}</p>

                @elseif ($notification->type == 'App\Notifications\RewardRedeemedNotification')
                    <!-- Reward Redeemed Notification -->
                    <p class="text-sm font-weight-bold">Reward Redeemed</p>
                    <p class="text-sm">{{ $notification->data['reward_name'] ?? 'Reward Details Unavailable' }}</p>
                    <p class="text-sm">{{ $notification->data['message'] ?? '' }}</p>

                    @elseif ($notification->type == 'App\Notifications\FeedbackSubmitted')
                        <!-- Feedback Submitted Notification -->
                        <p class="text-sm font-weight-bold">Feedback</p>
                        <p class="text-sm">{{ $notification->data['message'] }}</p>

                @else
                    <!-- Generic Fallback for Any Other Notification Type -->
                    <p class="text-sm font-weight-bold">{{ $notification->data['title'] ?? 'Notification' }}</p>
                    <p class="text-sm">{{ $notification->data['message'] ?? 'Details Unavailable' }}</p>
                @endif

                <p class="text-muted text-sm">
                    <i class="fas fa-clock mr-1"></i> {{ $notification->created_at->diffForHumans() }}
                </p>
            </div>
        </a>
        <form method="POST" action="{{ route('notifications.delete', $notification->id) }}" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger ml-2">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </div>
@endforeach


                </div>
                <!-- Footer Actions for Task Notifications -->
<div class="sticky-footer p-2 text-center d-flex flex-column justify-content-center align-items-center">
    <a href="{{ route('notifications.markAllAsRead') }}" class="btn btn-primary btn-sm mb-2">
        <i class="fas fa-check-circle mr-2"></i> Mark All as Read
    </a>
    <form method="POST" action="{{ route('notifications.deleteAllTask') }}">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">
            <i class="fas fa-trash-alt mr-2"></i> Delete All Notifications
        </button>
    </form>
</div>
            </div>
        </li>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
       <!-- Profile Dropdown Menu -->
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <span class="mr-2 d-none d-lg-inline small">{{ Auth::user()->name }}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right custom-dropdown">
        <a class="dropdown-item" href="{{ route('profile.index') }}">
            <i class="fas fa-user fa-sm fa-fw mr-2"></i>
            Profile
        </a>
        <a class="dropdown-item" href="{{ route('user.settings') }}">
            <i class="fas fa-cogs fa-sm fa-fw mr-2"></i>
            Settings
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2"></i>
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</li>
    </ul>
</nav>

<!-- Custom CSS -->
<!-- Custom CSS -->
<style>
   

    /* Button Styling */
    .btn {
        padding: 0.4rem 1rem;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Small Button Adjustments */
    .btn-sm {
        font-size: 0.8rem;
        padding: 0.25rem 0.75rem;
        display: inline-flex;
        align-items: center;
    }

    /* Dropdown Styles */
    .custom-dropdown {
        background-color: #343a40; /* Darker background for dropdown */
        color: #ffffff; /* Default text color for dropdown */
        border: 1px solid #444; /* Border to differentiate from other components */
    }

    /* Dropdown Item Styles */
    .custom-dropdown .dropdown-item {
        color: #ffffff; /* Default text color in dropdown */
        padding: 0.75rem 1.25rem;
    }

    /* Dropdown Icon Adjustments */
    .custom-dropdown .dropdown-item i {
        margin-right: 0.5rem;
        color: #ffffff; /* Ensure icons are visible */
    }

    /* Hover Effect */
    .custom-dropdown .dropdown-item:hover {
        background-color: #495057; /* Slightly lighter on hover */
        color: #ffffff !important;
    }

    /* Sticky Footer for Dropdowns */
    .sticky-footer a.btn, .sticky-footer button.btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }

    /* Scrollbar Customization */
    .custom-scroll {
        scrollbar-width: thin;
        scrollbar-color: #007bff #343a40;
    }

    .custom-scroll::-webkit-scrollbar {
        width: 8px;
    }

    .custom-scroll::-webkit-scrollbar-track {
        background: #343a40;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background-color: #007bff;
        border-radius: 10px;
        border: 2px solid #343a40;
    }

    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background-color: #0056b3;
    }

    /* Adjustments for Dark Mode Toggle Button */
    #darkModeToggle {
        background-color: #6c757d; /* Neutral background */
        color: #ffffff;
        border: none;
    }

    #darkModeToggle:hover {
        background-color: #5a6268; /* Darker on hover */
        color: #ffffff;
    }
</style>
<script>
    
    function adjustDropdownTextColor() {
    const dropdowns = document.querySelectorAll('.custom-dropdown');

    dropdowns.forEach(dropdown => {
        const bgColor = window.getComputedStyle(dropdown).backgroundColor;
        const brightness = getBrightness(rgbToHex(bgColor)); // Use your existing brightness function

        // If the background is too light, use darker text
        if (brightness > 128) {
            dropdown.style.color = '#000000'; // Dark text for light background
        } else {
            dropdown.style.color = '#ffffff'; // Light text for dark background
        }
    });
}
</script>
<script>
       $(document).on('click', '#markAllChatAsRead', function(e) {
    e.preventDefault();

    $.ajax({
        url: '{{ route("notifications.markAllChatAsRead") }}',
        method: 'GET',
        success: function(response) {
            // Update the notification count on the frontend
            $('.chat-notification-count').text('0');  // Reset count to zero
            $('.chat-notification-list').empty();     // Optionally, clear notification list
        },
        error: function(xhr, status, error) {
            console.log("An error occurred: " + error);
        }
    });
});


    $(document).on('click', '#markAllTaskAsRead', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("notifications.markAllTaskAsRead") }}',
            method: 'GET',
            success: function(response) {
                console.log("AJAX response received", response);
                // Update the task notification count on the front-end
                $('.task-notification-count').text(response.unreadTaskCount);

                // Optionally, you can also clear the notification dropdown if necessary
                $('.task-notification-list').empty();
            },
            error: function(xhr, status, error) {
                console.log("An error occurred: " + error);
            }
        });
    });
    </script>


      
<!-- /.navbar -->