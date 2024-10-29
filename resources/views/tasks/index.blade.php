<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="container-fluid">
        <!-- Container for List View and Kanban View buttons -->
        @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

        <div class="container-fluid d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div class="d-flex mb-3 flex-wrap">
                <!-- Toggle between List View and Kanban View -->
                <button class="btn btn-success mb-2 mr-2" id="listViewButton">
                    <i class="fas fa-list"></i> List View
                </button>
                <button class="btn btn-info mb-2 mr-2" id="kanbanViewButton">
                    <i class="fas fa-th"></i> Kanban View
                </button>
                <button class="btn btn-warning mb-2 mr-2" id="teamViewButton">
                    <i class="fas fa-users"></i> Team View
                </button>
            </div>
            <div>
                <!-- Create Task Button -->
                <a href="{{ route('user.tasks.create') }}" class="btn btn-success mb-2">
                    <i class="fas fa-plus"></i> Create Task
                </a>
            </div>
        </div>

        <!-- Filter Options -->
        <div class="row mb-4">
            <div class="col-md-2 col-sm-12 mb-2">
                <select class="form-control" id="priorityFilter">
                    <option value="">Filter by Priority</option>
                    <option value="high">High Priority</option>
                    <option value="medium">Medium Priority</option>
                    <option value="low">Low Priority</option>
                </select>
            </div>

            <div class="col-md-2 col-sm-12 mb-2">
                <select class="form-control" id="statusFilter">
                    <option value="">Filter by Status</option>
                    <option value="not started">Not Started</option>
                    <option value="in progress">In Progress</option>
                    <option value="done">Done</option>
                </select>
            </div>

            <div class="col-md-2 col-sm-12 mb-2">
                <select class="form-control" id="dueDateFilter">
                    <option value="">Filter by Due Date</option>
                    <option value="overdue">Overdue</option>
                    <option value="dueToday">Due Today</option>
                    <option value="dueThisWeek">Due This Week</option>
                </select>
            </div>

            <div class="col-md-2 col-sm-12 mb-2">
                <input type="date" class="form-control" id="startDateFilter" placeholder="From Date">
            </div>

            <div class="col-md-2 col-sm-12 mb-2">
                <input type="date" class="form-control" id="endDateFilter" placeholder="To Date">
            </div>

            <div class="col-md-2 col-sm-12 mb-2">
                <button class="btn btn-info btn-block" id="applyFilters">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
            </div>
        </div>

        <!-- Unified Task List View -->
        <div id="listView" style="display: none;">
            <div class="card card-primary card-outline shadow mb-4">
                <!-- AdminLTE Styled Header -->
                <div class="card-header">
                    <h3 class="card-title">All Tasks</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>

                <!-- Card body with table -->
                <div class="card-body p-0">
                    <div class="table-responsive p-2">
                        <table class="table table-bordered table-hover table-striped table-sm" id="dataTableUnified" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    @if (Auth::user()->usertype == 'admin')
                                        <th>User</th>
                                    @endif
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Due Date</th>
                                    <th>Date Started</th>
                                    <th>Duration</th>
                                    <th>Attachment</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allTasks as $task)
                                    <tr data-id="{{ $task->id }}">
                                        <td>{{ $task->name }}</td>
                                        @if (Auth::user()->usertype == 'admin')
                                            <td>{{ $task->user ? $task->user->name : 'Unknown' }}</td>
                                        @endif
                                        <td>
                                            <span class="badge" 
                                                data-priority="{{ $task->priority }}" 
                                                style="background-color: {{ $task->priority == 'high' ? '#ff6b6b' : ($task->priority == 'medium' ? '#feca57' : '#1dd1a1') }}; color: white;">
                                                {{ ucfirst($task->priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge" 
                                                data-status="{{ $task->status }}" 
                                                style="background-color: {{ $task->status == 'not started' ? '#54a0ff' : ($task->status == 'in progress' ? '#f6b93b' : '#78e08f') }}; color: white;">
                                                {{ ucfirst($task->status) }}
                                            </span>
                                        </td>
                                        <td>
                                        @php
                                                $dueDate = \Carbon\Carbon::parse($task->due_date);
                                                $today = \Carbon\Carbon::today();
                                            @endphp

                                            <span class="@if($dueDate->isPast()) text-danger @endif">
                                                {{ $dueDate->format('M d, Y') }}
                                            </span>
                                        </td>
                                        <td>{{ $task->date_started ? \Carbon\Carbon::parse($task->date_started)->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $task->duration ?? 'N/A' }}</td>
                                        <td>
                                            <!-- List group for attachments -->
                                            <ul class="list-group list-group-flush">
                                                @foreach ($task->attachments as $attachment)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center p-1">
                                                        {{ basename($attachment->file_path) }}
                                                        <div class="btn-group" role="group">
                                                            <!-- Download Button -->
                                                            <a href="{{ route('user.tasks.downloadAttachment', [$task->id, $attachment->id]) }}" class="btn btn-xs btn-success">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                            <!-- Preview Button -->
                                                            <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="btn btn-xs btn-warning">
                                                                <i class="fas fa-binoculars"></i>
                                                            </a>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <a href="{{ route('user.tasks.show', $task->id) }}" class="btn btn-xs btn-info custom-width">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-xs btn-danger custom-width" onclick="confirmDelete({{ $task->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
         <!-- Kanban View -->
         <div id="kanbanView" style="display: none;">
                <div class="row">
                    <!-- Not Started Column -->
                    <div class="col-md-4 kanban-column" id="notStarted">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3" style="background-color: #54a0ff;">
                                <h6 class="m-0 font-weight-bold text-white">Not Started</h6>
                            </div>
                            <div class="card-body">
                                @foreach ($tasksNotStarted as $task)
                                    <div class="card task-card" draggable="true" id="task-{{ $task->id }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="card-title font-weight-bold text-uppercase">{{ $task->name }}</h6>
                                                <div>
                                                    <a href="{{ route('user.tasks.show', $task->id) }}" class="text-primary mr-2">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm" style="color: red;" onclick="confirmDelete({{ $task->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="card-text">{{ $task->description }}</p>
                                            <p class="card-text">
                                                <span class="badge
                                                    @if($task->priority == 'high') badge-danger
                                                    @elseif($task->priority == 'medium') badge-warning
                                                    @else badge-success
                                                    @endif">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </p>
                                            <p class="card-text"><small class="text-muted">{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</small></p>
                                            @if (Auth::user()->usertype == 'admin')
                                                <p class="card-text"><small class="text-muted">Created by: {{ $task->user ? $task->user->name : 'Unknown' }}</small></p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- In Progress Column -->
                    <div class="col-md-4 kanban-column" id="inProgress">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3" style="background-color: #f6b93b;">
                                <h6 class="m-0 font-weight-bold text-white">In Progress</h6>
                            </div>
                            <div class="card-body">
                                @foreach ($tasksInProgress as $task)
                                    <div class="card task-card" draggable="true" id="task-{{ $task->id }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="card-title font-weight-bold text-uppercase">{{ $task->name }}</h6>
                                                <div>
                                                    <a href="{{ route('user.tasks.show', $task->id) }}" class="text-primary mr-2">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm" style="color: red;" onclick="confirmDelete({{ $task->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="card-text">{{ $task->description }}</p>
                                            <p class="card-text">
                                                <span class="badge
                                                    @if($task->priority == 'high') badge-danger
                                                    @elseif($task->priority == 'medium') badge-warning
                                                    @else badge-success
                                                    @endif">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </p>
                                            <p class="card-text"><small class="text-muted">{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</small></p>
                                            @if (Auth::user()->usertype == 'admin')
                                                <p class="card-text"><small class="text-muted">Created by: {{ $task->user ? $task->user->name : 'Unknown' }}</small></p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Done Column -->
                    <div class="col-md-4 kanban-column" id="done">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3" style="background-color: #78e08f;">
                                <h6 class="m-0 font-weight-bold text-white">Done</h6>
                            </div>
                            <div class="card-body">
                                @foreach ($tasksDone as $task)
                                    <div class="card task-card" draggable="true" id="task-{{ $task->id }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="card-title font-weight-bold text-uppercase">{{ $task->name }}</h6>
                                                <div>
                                                    <a href="{{ route('user.tasks.show', $task->id) }}" class="text-primary mr-2">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm" style="color: red;" onclick="confirmDelete({{ $task->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="card-text">{{ $task->description }}</p>
                                            <p class="card-text">
                                                <span class="badge
                                                    @if($task->priority == 'high') badge-danger
                                                    @elseif($task->priority == 'medium') badge-warning
                                                    @else badge-success
                                                    @endif">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </p>
                                            <p class="card-text"><small class="text-muted">{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</small></p>
                                            @if (Auth::user()->usertype == 'admin')
                                                <p class="card-text"><small class="text-muted">Created by: {{ $task->user ? $task->user->name : 'Unknown' }}</small></p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Team Tasks Section with AdminLTE Styling -->
        <div class="card card-primary card-outline shadow mb-4" id="teamTasksSection" style="display: none;">
            <!-- AdminLTE Styled Header -->
            <div class="card-header">
                <h3 class="card-title">Team Tasks</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <!-- Card body with table -->
            <div class="card-body p-0">
                <div class="table-responsive p-2">
                    <table class="table table-bordered table-hover table-striped table-sm" id="teamTasksTable">
                        <thead class="thead-light">
                            <tr>
                                <th>Task Name</th>
                                <th>Team Name</th>
                                @if (Auth::user()->usertype == 'admin') <!-- Conditionally for admin -->
                                    <th>Assigned To</th>
                                @else
                                    <th>Created By</th>
                                @endif
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Due Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teamTasks as $task)
                                <tr>
                                    <td>{{ $task->name }}</td>
                                    <td>{{ $task->team->name }}</td>
                                    @if (Auth::user()->usertype == 'admin')
                                        <td>{{ $task->user->name }}</td> <!-- For admin: show who the task is assigned to -->
                                    @else
                                        <td>{{ $task->team->creator->name }}</td> <!-- For user: show the team creator -->
                                    @endif
                                    <td>
                                        <span class="badge" 
                                            data-status="{{ $task->status }}" 
                                            style="background-color: {{ $task->status == 'not started' ? '#54a0ff' : ($task->status == 'in progress' ? '#f6b93b' : '#78e08f') }}; color: white;">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                    </td>
                                                                            <td>
                                        <span class="badge" 
                                            data-priority="{{ $task->priority }}" 
                                            style="background-color: {{ $task->priority == 'high' ? '#ff6b6b' : ($task->priority == 'medium' ? '#feca57' : '#1dd1a1') }}; color: white;">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                                $dueDate = \Carbon\Carbon::parse($task->due_date);
                                                $today = \Carbon\Carbon::today();
                                            @endphp

                                            <span class="@if($dueDate->isPast()) text-danger @endif">
                                                {{ $dueDate->format('M d, Y') }}
                                            </span>
                                        </td>
                                    <td class="text-center"> <!-- Center align action buttons -->
                                        <a href="{{ route('user.tasks.show', $task->id) }}" class="btn btn-info btn-xs">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


</x-app-layout>

<style>
/* Target the DataTable length label for the "Show entries" in light mode */
#dataTableUnified_wrapper .dataTables_length label {
    color: #333; /* Darker text color for light mode */
    font-weight: bold; /* Make the text stand out */
    font-size: 14px; /* Adjust the font size for better visibility */
    cursor: default; /* Use default cursor for labels */
}

/* For dark mode, adjust the text to be lighter */
body.dark-mode #dataTableUnified_wrapper .dataTables_length label {
    color: #e0e0e0; /* Light text color for visibility in dark mode */
    font-weight: bold;
    font-size: 14px;
    cursor: default; /* Use default cursor for labels */
}

/* Styling for DataTables info section */
#dataTableUnified_wrapper .dataTables_info {
    color: #333; /* Darker color for light mode */
    font-size: 14px;
    cursor: default; /* Use default cursor for info */
}

/* Dark mode adjustment for info section */
body.dark-mode #dataTableUnified_wrapper .dataTables_info {
    color: #e0e0e0; /* Light color for visibility in dark mode */
    cursor: default; /* Use default cursor for info */
}

/* Styling for DataTables search label */
#dataTableUnified_wrapper .dataTables_filter label {
    color: #333; /* Darker color for light mode */
    font-size: 14px;
    font-weight: bold; /* Emphasize the label */
    cursor: default; /* Use default cursor for labels */
}

/* Dark mode adjustment for search label */
body.dark-mode #dataTableUnified_wrapper .dataTables_filter label {
    color: #e0e0e0; /* Lighter color for visibility in dark mode */
    font-weight: bold;
    cursor: default; /* Use default cursor for labels */
}

/* Light mode styling for 'Previous' and 'Next' text */
#dataTableUnified_wrapper .pagination .paginate_button a.page-link {
    color: #333; /* Dark text color for light mode */
    font-weight: bold; /* Make the text stand out */
    cursor: pointer; /* Ensure the cursor changes to pointer */
}

/* Hover state for 'Previous' and 'Next' text in light mode */
#dataTableUnified_wrapper .pagination .paginate_button a.page-link:hover {
    color: #000; /* Darker color on hover for visibility */
}

/* Dark mode styling for 'Previous' and 'Next' text */
body.dark-mode #dataTableUnified_wrapper .pagination .paginate_button a.page-link {
    color: #f1f1f1; /* Light text color for dark mode */
    font-weight: bold;
    cursor: pointer; /* Ensure the cursor changes to pointer */
}

/* Hover state for 'Previous' and 'Next' text in dark mode */
body.dark-mode #dataTableUnified_wrapper .pagination .paginate_button a.page-link:hover {
    color: #ffffff; /* Pure white text on hover for maximum visibility */
}
    /* Add this to your existing CSS */
.task-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.task-card.dragging {
    opacity: 0.5; /* Change opacity while dragging */
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    transform: scale(1.05); /* Slightly scale up the card */
}

</style>
<!-- Custom delete confirmation modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this task?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Ensure you have the necessary FontAwesome and SB Admin 2 scripts included -->
<link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<!--<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>-->
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/sb-admin-2.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Custom Scripts -->
<script>
    $(document).ready(function() {
        // Automatically close the alert after 5 seconds
        setTimeout(function() {
            $(".alert").alert('close');
        }, 5000);
    });
</script>
<script>
$(document).ready(function() {
    var dataTables = {};

    // Initialize DataTable for both task lists (My Tasks and Team Tasks)
    dataTables['dataTableUnified'] = $('#dataTableUnified').DataTable();  // My Tasks
    dataTables['teamTasksTable'] = $('#teamTasksTable').DataTable();  // Team Tasks

    // Function to handle button color change (active view highlighting)
    function handleButtonColorChange(activeButton) {
        $('#viewOptions button').removeClass('btn-success').addClass('btn-info');
        $(activeButton).removeClass('btn-info').addClass('btn-success');
    }

    // Function to toggle between List and Kanban Views (and My/Team Tasks in List View)
    function toggleView(viewToShow) {
        $('#listView, #kanbanView, #teamTasksSection').hide(); // Hide all views
        $(viewToShow).show();
    }

    // Initialize List View by default
    function initializeView() {
        $('#listViewButton').addClass('btn-success');
        $('#kanbanViewButton').removeClass('btn-success').addClass('btn-info');
        $('#teamViewButton').removeClass('btn-success').addClass('btn-info');
        toggleView('#listView'); // Show My Tasks by default
    }

    // Event handler for List View button (My Tasks)
    $('#listViewButton').on('click', function() {
        toggleView('#listView'); // Show My Tasks
        $('#teamTasksSection').hide(); // Hide Team Tasks
        $(this).removeClass('btn-info').addClass('btn-success');
        $('#kanbanViewButton').removeClass('btn-success').addClass('btn-info');
        $('#teamViewButton').removeClass('btn-success').addClass('btn-info');
    });

    // Event handler for Team View button (Team Tasks)
    $('#teamViewButton').on('click', function() {
        toggleView('#teamTasksSection'); // Show Team Tasks
        $('#listView').hide(); // Hide My Tasks
        $(this).removeClass('btn-info').addClass('btn-success');
        $('#listViewButton').removeClass('btn-success').addClass('btn-info');
        $('#kanbanViewButton').removeClass('btn-success').addClass('btn-info');
    });

    // Event handler for Kanban View button
    $('#kanbanViewButton').on('click', function() {
        toggleView('#kanbanView'); // Show Kanban View
        $(this).removeClass('btn-info').addClass('btn-success');
        $('#listViewButton').removeClass('btn-success').addClass('btn-info');
        $('#teamViewButton').removeClass('btn-success').addClass('btn-info');
    });

    // Kanban drag-and-drop functionality for updating task status
    $('.task-card').on('dragstart', function(event) {
        event.originalEvent.dataTransfer.setData("text", event.target.id);
        // Add dragging class for visual feedback
        $(this).addClass('dragging');
    });

    $('.task-card').on('dragend', function(event) {
        // Remove the dragging class after drag ends
        $(this).removeClass('dragging');
    });

    $('.kanban-column').on('dragover', function(event) {
        event.preventDefault();
    });

    $('.kanban-column').on('drop', function(event) {
        event.preventDefault();
        var taskId = event.originalEvent.dataTransfer.getData("text");
        var taskElement = document.getElementById(taskId);
        var targetColumn = event.target.closest('.kanban-column');
        if (targetColumn) {
            targetColumn.querySelector('.card-body').appendChild(taskElement);
            updateTaskStatus(taskId.split('-')[1], targetColumn.id);
        }
    });

    // Update task status via AJAX after dragging in Kanban
    // Update task status via AJAX after dragging in Kanban
    function updateTaskStatus(taskId, status) {
    var statusMap = {
        notStarted: 'not started',
        inProgress: 'in progress',
        done: 'done'
    };

    $.ajax({
        url: '/tasks/' + taskId + '/status',
        type: 'PATCH',
        data: {
            _token: '{{ csrf_token() }}',
            status: statusMap[status]
        },
        success: function(response) {
            console.log(response.message);
            // Update the task in the list view after the status change
            moveTaskInListView(taskId, statusMap[status], response.date_started, response.duration);
            // If task status is done, handle approval/rejection UI update
            if (response.status === 'Done' && response.approval_status === 'Pending') {
                // Show the approval/rejection buttons dynamically here
                console.log('Task approval pending');
            }
        },
        error: function(xhr) {
            console.error('Error updating task status:', xhr.responseText);
        }
    });
}


// Move task in list view after status update
function moveTaskInListView(taskId, status, dateStarted, duration) {
    // Locate the task row in the table using its task ID
    var taskRow = $('tr[data-id="' + taskId + '"]').first();

    // Determine if the user is an admin (this affects column positioning)
    var isAdmin = {{ Auth::user()->usertype == 'admin' ? 'true' : 'false' }};

    // Adjust column indices based on whether the 'User' column is present (only for admins)
    var statusColumnIndex = isAdmin ? 3 : 2;
    var dateStartedColumnIndex = isAdmin ? 5 : 4;
    var durationColumnIndex = isAdmin ? 6 : 5; // The column for 'Duration'

    // If the task has a start date, format it into a readable format
    if (dateStarted) {
        var dateObj = new Date(dateStarted);
        dateStarted = dateObj.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }

    // Update the relevant table columns with the new values
    taskRow.find('td:eq(' + dateStartedColumnIndex + ')').text(dateStarted || 'N/A');  // Update the 'Date Started' column
    taskRow.find('td:eq(' + durationColumnIndex + ')').text(duration || 'N/A');  // Update the 'Duration' column

    // Find the span inside the status column
    var statusLabel = taskRow.find('td:eq(' + statusColumnIndex + ') span');
    
    // Update the text inside the span
    statusLabel.text(capitalizeWords(status));
    
    // Update the background color of the status label
    var statusColor = status === 'not started' ? '#54a0ff' : (status === 'in progress' ? '#f6b93b' : '#78e08f');
    statusLabel.css('background-color', statusColor); // Set background color
    statusLabel.css('color', 'white'); // Set text color to white for better readability


    // Redraw the DataTable row to reflect the changes
    dataTables['dataTableUnified'].row(taskRow).invalidate().draw();
}

// Helper function to capitalize the first letter of each word in a string
function capitalizeWords(str) {
    return str.replace(/\b\w/g, function(char) { return char.toUpperCase(); });
}



    // Apply filters for priority, status, and date range
   // Apply filters for priority, status, and date range
$('#applyFilters').on('click', function() {
    dataTables['dataTableUnified'].draw();  // Redraw the table with the filters applied
    dataTables['teamTasksTable'].draw();  // Redraw the team tasks table with the filters applied
});


    // Custom DataTables search function for filters (includes date range)
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
    var priorityFilter = $('#priorityFilter').val().toLowerCase();
    var statusFilter = $('#statusFilter').val().toLowerCase();
    var dueDateFilter = $('#dueDateFilter').val();  // Get the due date filter value
    var startDateFilter = $('#startDateFilter').val();  // From Date filter
    var endDateFilter = $('#endDateFilter').val();  // To Date filter

    // Check if the table being filtered is either My Tasks or Team Tasks
    var tableId = settings.nTable.id;  // Get the ID of the table being filtered

    // Adjust column indices based on the table being filtered
    var priorityColumnIndex, statusColumnIndex, dueDateColumnIndex;
    var isAdmin = {{ Auth::user()->usertype == 'admin' ? 'true' : 'false' }};

    if (tableId === 'dataTableUnified') {
        // Unified Task List
        priorityColumnIndex = isAdmin ? 2 : 1;
        statusColumnIndex = isAdmin ? 3 : 2;
        dueDateColumnIndex = isAdmin ? 4 : 3;
    } else if (tableId === 'teamTasksTable') {
        // Team Task List
        priorityColumnIndex = 4;  // Column for priority
    statusColumnIndex = 3;    // Column for status (already set)
    dueDateColumnIndex = 5;   // Column for due date
    } else {
        return true;  // If the table is not recognized, don't filter
    }

    // Proceed with filtering logic
    var taskPriority = $(settings.aoData[dataIndex].nTr).find('td span[data-priority]').data('priority').toLowerCase();
    var taskStatus = $(settings.aoData[dataIndex].nTr).find('td span[data-status]').data('status').toLowerCase();
    var dueDate = new Date(data[dueDateColumnIndex]);  // Due Date column
    var today = new Date();

    // Strip time from today's date
    today.setHours(0, 0, 0, 0);

    // Strip time from the dueDate for comparison
    dueDate.setHours(0, 0, 0, 0);

    // Filter by priority
    if (priorityFilter && taskPriority !== priorityFilter) {
        return false;
    }

    // Filter by status
    if (statusFilter && taskStatus !== statusFilter) {
        return false;
    }

    function getStartOfWeek() {
    var today = new Date();
    var day = today.getDay();
    var diff = today.getDate() - day + (day === 0 ? -6 : 1); // Adjust when day is Sunday (0)
    return new Date(today.setDate(diff));
}

// Helper function to get the end of the current week (Sunday)
function getEndOfWeek() {
    var startOfWeek = getStartOfWeek();
    return new Date(startOfWeek.setDate(startOfWeek.getDate() + 6));
}

    // Calculate the start and end of this week

    var startOfWeek = getStartOfWeek();

    var endOfWeek = getEndOfWeek();

    // Apply "Overdue", "Due Today", "Due This Week" logic
    if (dueDateFilter === 'overdue' && dueDate >= today) {
        return false; // Overdue: Due date must be before today
    }
    if (dueDateFilter === 'dueToday' && dueDate.getTime() !== today.getTime()) {
        return false; // Due Today: Due date must be exactly today
    }
    if (dueDateFilter === 'dueThisWeek' && (dueDate < startOfWeek || dueDate > endOfWeek)) {
        return false; // Due This Week: Due date must be within the current week
    }

    // Apply "From" and "To" date range filter (if filled)
    if (startDateFilter) {
        var fromDate = new Date(startDateFilter);
        fromDate.setHours(0, 0, 0, 0); // Normalize to the start of the day

        if (dueDate < fromDate) {
            return false; // Due date is before the "From" date
        }
    }

    if (endDateFilter) {
        var toDate = new Date(endDateFilter);
        toDate.setHours(23, 59, 59, 999); // Normalize to the end of the day

        if (dueDate > toDate) {
            return false; // Due date is after the "To" date
        }
    }

    return true;  // If all filters pass, show the row
});


    // Confirm delete modal logic
    function confirmDelete(taskId) {
        taskIdToDelete = taskId;
        $('#deleteModal').modal('show');
    }

    $('#confirm-delete-btn').on('click', function() {
        if (taskIdToDelete) {
            deleteTask(taskIdToDelete);
        }
    });


    // Handle task deletion via AJAX
    function deleteTask(taskId) {
        var url = '/tasks/' + taskId;  // This URL works for both admin and users if your route is set properly.

        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#deleteModal').modal('hide');
                var row = $('tr[data-id="' + taskId + '"]');
                dataTables['dataTableUnified'].row(row).remove().draw();

                var taskCard = document.getElementById('task-' + taskId);
                if (taskCard) {
                    taskCard.remove();
                }
            },
            error: function(xhr) {
                console.error('Error deleting task:', xhr.responseText);
                $('#deleteModal').modal('hide');
            }
        });
    }

    window.confirmDelete = confirmDelete;

    // Initialize the default view as List View
    initializeView();
});

</script>
