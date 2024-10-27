<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/dashboard') }}" class="brand-link">
        <img src="{{ asset('dist/img/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-square elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Collaboratask</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('dist/img/avatar5.png') }}"
                     class="rounded-full mx-auto md:mx-0 object-cover"
                     alt="Profile Picture"
                     style="width: 40px; height: 40px; object-fit: cover;">
            </div>
            <div class="info">
                <a href="{{ route('profile.index') }}" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <style>
            .user-panel {
                padding: 10px 15px;  /* Example consistent padding */
                margin-bottom: 20px; /* Example spacing between profile and menu */
            }

            .user-panel .image {
                margin-right: 10px;  /* Space between image and name */
            }

            .user-panel img {
                width: 40px;  /* Consistent image size */
                height: 40px;
                border-radius: 50%; /* Circular images */
            }

            .nav-item {
                margin-bottom: 10px !important;  /* Space between each nav item */
            }

            .nav-link {
                padding: 10px 15px !important;  /* Consistent padding inside each link */
            }

            .nav-treeview {
                padding-left: 20px; /* Indentation for child menu items */
            }
        </style>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ url('/dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Tasks Menu -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>
                            Tasks
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('user.tasks.create') }}" class="nav-link">
                                <p>Create Task</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.tasks.index') }}" class="nav-link">
                                <p>List Tasks</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Teams Menu -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Teams
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('user.teams.create') }}" class="nav-link">
                                <p>Create Team</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.teams.index') }}" class="nav-link">
                                <p>List Teams</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('invitations.index') }}" class="nav-link">
                                <p>Pending Invitations</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Task Calendar -->
                <li class="nav-item">
                    <a href="{{ route('calendar.view') }}" class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>Task Calendar</p>
                    </a>
                </li>

                <!-- Messages Menu -->
                <li class="nav-item">
                    <a href="{{ route('messages.all') }}" class="nav-link">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Messages</p>
                    </a>
                </li>

      
<!-- Feedback Menu Item -->
<li class="nav-item">
    <a href="{{ route('feedback.create') }}" class="nav-link">
        <i class="nav-icon fas fa-comment-dots"></i>
        <p>Feedback</p>
    </a>
</li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
