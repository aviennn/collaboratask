<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Team Dashboard') }}
</h2>

<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

        <!-- Bootstrap CSS 
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">-->

        <!-- Bootstrap JS and dependencies 
        <script src="https://cdn.jsdelivr.com/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>-->
        <style>
            /* Add padding to the table container for better spacing */
.table-responsive {
    padding: 15px; /* Adjust the padding value as needed */
}

.card-body {
    padding: 20px; /* Adjust to your preference */
}
/* Directly modify table padding */
#allTeamTasksTable {
    padding: 10px; /* Adjust the padding value */
}
/* Adjust DataTables container styling */
.dataTables_wrapper {
    padding: 10px; /* Add some padding inside the DataTables wrapper */
}

/* Adjust table cell padding */
#allTeamTasksTable th, #allTeamTasksTable td {
    padding: 12px 10px; /* Adjust vertical and horizontal padding */
}

/* Adjust the size and padding of the dropdown */
.dataTables_length select {
    width: 100px;       /* Adjust the width as needed */
    padding: 8px 12px;  /* Increase padding for better spacing */
    border-radius: 5px; /* Add rounded corners */
    margin-left: 5px;   /* Space between label and dropdown */
    font-size: 14px; /* Adjust the font size */

}
/* Align the dropdown with the label */
.dataTables_length {
    display: flex;
    align-items: center;
    margin-bottom: 10px; /* Space below the dropdown */
}
/* Remove focus outline */
.dataTables_length select:focus {
    outline: none;
    box-shadow: 0 0 5px #007bff; /* Optional: Add a subtle focus shadow */
}


            .file-preview a {
    color: #ffffff;  /* Set link color to white */
    text-decoration: underline;  /* Underline the link */
}

.file-preview a:hover {
    color: #00ffcc;  /* Change color on hover to make it more visible */
    text-decoration: underline;
}
            /* Style for the current user's message */
            .file-preview img {
    max-width: 150px;
    border: 1px solid #ccc;
    padding: 5px;
    margin-top: 10px;
}
.current-user-message {
    justify-content: flex-end; /* Aligns the message to the right */
    display: flex;
}

.current-user-message .media-body {
    background-color: #007bff; /* Blue background for current user */
    color: white;
    max-width: 60%; /* Adjust to desired width */
    padding: 10px;
    border-radius: 10px;
    display: inline-block; /* Make sure the box fits only the text */
    word-wrap: break-word;
}

/* Style for other users' messages */
.other-user-message {
    justify-content: flex-start; /* Aligns the message to the left */
    display: flex;
}

.other-user-message .media-body {
    background-color: #d6eaff; /* Light blue background for other users */
    color: black;
    max-width: 60%; /* Adjust to desired width */
    padding: 10px;
    border-radius: 10px;
    display: inline-block; /* Make sure the box fits only the text */
    word-wrap: break-word;
}

/* Profile picture alignment */
.media img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.nav-link i {
    margin-left: 10px;  /* Consistent margin for all icons */
    margin-right: 10px;
}

.navbar-nav .nav-item {
    margin-left: 15px;  /* Spacing between each nav item */
    margin-right: 15px;
}

/* Ensure all icons are of the same size */
.nav-link i {
    font-size: 1rem;  /* Adjust icon size if needed */
}

canvas {
    max-width: 600px;
    max-height: 400px;
}

.chart-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 20px;
}


        </style>
    </x-slot>

    <div class="py-12 px-4">
        <div class="row justify-content-center">
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

            <!-- Tabs for Dashboard and Leaderboard -->
            <div class="col-md-12 mb-4">
                <ul class="nav nav-tabs" id="teamTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="dashboard-tab" data-toggle="tab" href="#dashboard" role="tab" aria-controls="dashboard" aria-selected="true">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tasks-tab" data-toggle="tab" href="#tasks" role="tab" aria-controls="tasks" aria-selected="false">All Tasks</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="chat-tab" data-toggle="tab" href="#chat" role="tab">Chat</a>
                    </li>
                    @if ($team->has_rewards)
                    <li class="nav-item">
                        <a class="nav-link" id="leaderboard-tab" data-toggle="tab" href="#leaderboard" role="tab" aria-controls="leaderboard" aria-selected="false">Leaderboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="rewards-tab" data-toggle="tab" href="#rewards" role="tab" aria-controls="rewards" aria-selected="false">Rewards Shop</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" id="reports-tab" data-toggle="tab" href="#reports" role="tab" aria-controls="reports" aria-selected="false">Reports</a>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="tab-content col-md-12" id="teamTabContent">
                    <!-- Dashboard Tab -->
                    <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                    <!-- Team Info and Members -->
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Team Info</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Name:</strong> {{ $team->name }}</p>
                                    <p><strong>Description:</strong> {{ $team->description }}</p>
                                    <p><strong>Creator:</strong> {{ $team->creator->name }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 mb-4">
                            <div class="card shadow">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Members</h6>
                                </div>
                                <div class="card-body">
                                <ul class="list-group mb-4">
                @foreach ($team->members as $member)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $member->name }}</span>
                        <!-- Add View Button -->
                        <a href="{{ route('user.analytics', ['team_id' => $team->id, 'user_id' => $member->id]) }}" class="btn btn-info btn-sm">
                            View User
                        </a>
                    </li>
                @endforeach
            </ul>

                                    @if(Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id)
                                        <!-- Assign Task Button -->
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#assignTaskModal">
                                            Assign Task
                                        </button>
                                    @endif

                                    <!-- Task Assignment Modal -->
                                    <div class="modal fade" id="assignTaskModal" tabindex="-1" role="dialog" aria-labelledby="assignTaskModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="assignTaskModalLabel">Assign Task</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{ route('team.tasks.store', $team->id) }}" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="name">Task Name</label>
                                                            <input type="text" name="name" id="name" class="form-control" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="priority">Priority</label>
                                                            <select name="priority" id="priority" class="form-control" required>
                                                                <option value="low">Low</option>
                                                                <option value="medium">Medium</option>
                                                                <option value="high">High</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="due_date">Due Date</label>
                                                            <input type="date" name="due_date" id="due_date" class="form-control" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="description">Description</label>
                                                            <textarea name="description" id="description" class="form-control"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="attachments">Attachments</label>
                                                            <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                                                        </div>
                                                        <div class="form-group">
                                                        <label for="checklists">Checklist Items</label>
                                                            <div id="checklist-items">
                                                                <div class="input-group mb-2 checklist-group">
                                                                    <input type="text" name="checklists[]" class="form-control checklist-input" placeholder="Checklist item">
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-danger remove-checklist-item" type="button">Remove</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- + Button for adding checklist item -->
                                                            <button id="add-checklist-item" class="btn btn-success mt-2" type="button">
                                                                <i class="fas fa-plus"></i> <!-- Font Awesome icon for "+" -->
                                                            </button>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="assignee">Assign to Member</label>
                                                            <select name="assignee" id="assignee" class="form-control" required>
                                                                @foreach ($team->members as $member)
                                                                    <option value="{{ $member->id }}">{{ $member->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group mt-3">
                                                            <button type="submit" class="btn btn-primary">Assign Task</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Task Assignment Modal -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assigned Tasks Section -->
<div class="col-md-12 mt-4">
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
            @if(Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id)
            Tasks Pending Approval
                @else
                    My Assigned Tasks
                @endif
            </h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Assigned To</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        @if(Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id)
                            <th>Approval Status</th>
                        @endif
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <td>{{ $task->name }}</td>
                            <td>{{ $task->assignee->name }}</td>
                            <td>
                                <span class="badge badge-{{ $task->priority == 'high' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'success') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $task->status == 'not started' ? 'secondary' : ($task->status == 'in progress' ? 'primary' : 'success') }}">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</td>
                            @if(Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id)
                                <td>
                                    @if($task->approval_status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($task->approval_status == 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                            @endif
                            <td>
                                <a href="{{ route('user.tasks.show', $task->id) }}" class="btn btn-info btn-sm">View</a>

                                @if(Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id && $task->status == 'done' && $task->approval_status == 'pending')
    <!-- Approve Button -->
    <form method="POST" action="{{ route('user.tasks.approveOrReject', $task->id) }}" class="d-inline">
        @csrf
        @method('PUT')
        <input type="hidden" name="approval_status" value="approved">
        <button type="submit" class="btn btn-success btn-sm">Approve</button>
    </form>

     <!-- Reject Button -->
<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModalFirstView{{ $task->id }}">
    Reject
</button>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModalFirstView{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabelFirstView{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('user.tasks.approveOrReject', $task->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabelFirstView{{ $task->id }}">Reject Task: {{ $task->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_remarks">Rejection Remarks</label>
                        <textarea name="rejection_remarks" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="approval_status" value="rejected">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Submit Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- End of Assigned Tasks Section -->

<!-- Tasks to Grade Section (Visible Only if Rewards Enabled) -->
@if($team->has_rewards && Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id)
    <div class="col-md-12 mt-4">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Tasks to Grade</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Task Name</th>
                            <th>Assigned To</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasksToGrade as $task)
                            <tr>
                                <td>{{ $task->name }}</td>
                                <td>{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</td>
                                <td>
                                    <span class="badge badge-{{ $task->priority == 'high' ? 'danger' : ($task->priority == 'medium' ? 'warning' : 'success') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $task->status == 'done' ? 'success' : ($task->status == 'in progress' ? 'primary' : 'secondary') }}">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</td>
                                <td>
                                      <!-- Grade button -->
                                      <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#gradeModalFirstView{{ $task->id }}">
    Grade
</button>
                                </td>
                            </tr>

                            <!-- Grade Modal -->
                            <div class="modal fade" id="gradeModalFirstView{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="gradeModalLabelFirstView{{ $task->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('user.tasks.grade', $task->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="gradeModalLabelFirstView{{ $task->id }}">Grade Task: {{ $task->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="grade">Select Grade</label>
                        <select name="grade" id="grade" class="form-control" required>
                            <option value="good">Good</option>
                            <option value="very good">Very Good</option>
                            <option value="excellent">Excellent</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Grade</button>
                </div>
            </form>
        </div>
    </div>
</div>
                            <!-- End Grade Modal -->
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

                </div>

<!-- Reports Tab -->
<div class="tab-pane fade" id="reports" role="tabpanel" aria-labelledby="reports-tab">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-header py-4 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top">
            <h6 class="m-0 font-weight-bold text-uppercase">Team Reports</h6>
        </div>
        <div class="card-body">
            <h3 class="text-center text-primary">Generate Report by Date Range</h3>
            <form id="filterForm" class="mb-4 d-flex justify-content-center align-items-end">
    <div class="form-group mx-2">
        <label for="start_date" class="font-weight-bold">Start Date:</label>
        <input type="date" class="form-control" id="start_date" name="start_date">
    </div>
    <div class="form-group mx-2">
        <label for="end_date" class="font-weight-bold">End Date:</label>
        <input type="date" class="form-control" id="end_date" name="end_date">
    </div>
    <div class="form-group mx-2">
        <button type="button" class="btn btn-primary px-4" id="filterButton">Filter</button>
    </div>
</form>


            <!-- Compare Reports Button -->
            <button type="button" class="btn btn-info mb-4 w-100 py-2" data-toggle="modal" data-target="#comparisonModal">
                Compare Reports
            </button>
<!-- Add Task Completion Metrics Section in Reports Tab -->
<h3 class="text-center text-primary mb-4">Task Completion Metrics</h3>

<!-- Task Metrics Row 1 -->
<div class="row mb-4">
    <!-- Task Completion Rate per Member -->
    <div class="col-md-4">
        <div class="p-3 bg-light rounded shadow-sm">
            <h5 class="text-center text-dark">Task Completion Rate</h5>
            <ul class="list-group list-group-flush">
                @foreach($team->members as $member)
                    @php
                        $totalTasks = $team->tasks()->where('user_id', $member->id)->count();
                        $completedTasks = $team->tasks()->where('user_id', $member->id)->where('status', 'done')->count();
                        $completionRate = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                    @endphp
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $member->name }}</span>
                        <span class="badge badge-primary badge-pill">{{ round($completionRate, 2) }}%</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Overdue Tasks -->
    <div class="col-md-4">
        <div class="p-3 bg-light rounded shadow-sm">
            <h5 class="text-center text-danger">Overdue Tasks</h5>
            <ul class="list-group list-group-flush">
                @foreach($team->tasks()->where('status', '!=', 'done')->where('due_date', '<', now())->get() as $task)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $task->name }}</span>
                        <small class="text-muted">Due: {{ $task->due_date->format('M d, Y') }}</small>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- On-time Task Delivery -->
    <div class="col-md-4">
        <div class="p-3 bg-light rounded shadow-sm">
            <h5 class="text-center text-success">On-time Task Delivery</h5>
            <ul class="list-group list-group-flush">
                @foreach($team->tasks()->where('status', 'done')->get() as $task)
                    @if($task->due_date && $task->date_completed && $task->due_date >= $task->date_completed)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $task->name }}</span>
                            <small class="text-muted">Completed: {{ $task->date_completed->format('M d, Y') }}</small>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>

<!-- Task Metrics Row 2 -->
<div class="row mb-4">
    <!-- Number of Tasks Assigned per Member -->
    <div class="col-md-4">
        <div class="p-3 bg-light rounded shadow-sm">
            <h5 class="text-center text-dark">Tasks Assigned per Member</h5>
            <ul class="list-group list-group-flush">
                @foreach($team->members as $member)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $member->name }}</span>
                        <span class="badge badge-secondary">{{ $team->tasks()->where('user_id', $member->id)->count() }} tasks</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Top Performers (Most Tasks Completed) -->
    <div class="col-md-4">
        <div class="p-3 bg-light rounded shadow-sm">
            <h5 class="text-center text-primary">Top Performers</h5>
            <ul class="list-group list-group-flush">
                @php
                    $topPerformers = $team->members()
                        ->withCount(['tasks as completed_tasks' => function ($query) use ($team) {
                            $query->where('status', 'done')
                                  ->where('team_id', $team->id);  // Ensure the tasks are within the team
                        }])
                        ->orderBy('completed_tasks', 'desc')
                        ->get();
                @endphp
                @foreach($topPerformers as $performer)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $performer->name }}</span>
                        <span class="badge badge-success">{{ $performer->completed_tasks }} tasks</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    @if($team->has_rewards)
    <!-- Top Scorers (Only if team has rewards) -->
    <div class="col-md-4">
        <div class="p-3 bg-light rounded shadow-sm">
            <h5 class="text-center text-warning">Top Scorers</h5>
            <ul class="list-group list-group-flush">
                @php
                    $topScorers = $team->members()
                        ->withSum(['tasks as total_points' => function ($query) use ($team) {
                            $query->where('team_id', $team->id);  // Ensure the tasks are within the team
                        }], 'points')
                        ->orderBy('total_points', 'desc')
                        ->get();
                @endphp
                @foreach($topScorers as $scorer)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $scorer->name }}</span>
                        <span class="badge badge-warning">{{ $scorer->total_points !== null ? $scorer->total_points . ' points' : '0 points' }}
                        points</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>

<!-- Task Metrics Row 3 -->
<div class="row mb-4">
    <!-- Average Time to Complete Tasks per Member -->
    <div class="col-md-4">
        <div class="p-3 bg-light rounded shadow-sm">
            <h5 class="text-center text-info">Average Task Completion Time</h5>
            <ul class="list-group list-group-flush">
                @foreach($team->members as $member)
                    @php
                        $averageTime = $team->tasks()
                            ->where('user_id', $member->id)
                            ->whereNotNull('date_started')
                            ->whereNotNull('date_completed')
                            ->get()
                            ->map(function ($task) {
                                return $task->date_started->diffInHours($task->date_completed);
                            })->avg();
                    @endphp
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $member->name }}</span>
                        <span class="badge badge-info">{{ $averageTime ? round($averageTime, 2) . ' hours' : 'N/A' }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

                            <!-- Chart Type Dropdown -->
  <!-- Chart Type Dropdown -->
  <div class="form-group">
                <label for="chartType" class="font-weight-bold">Select Chart Type:</label>
                <select id="chartType" class="form-control shadow-sm">
                    <option value="pie">Pie Chart</option>
                    <option value="bar">Bar Chart</option>
                    <option value="line">Line Chart</option>
                </select>
            </div>

            <!-- Charts Section -->
            <h3 class="text-center mt-5 text-info">Task Statuses</h3>
            <div class="chart-container">
                <canvas id="taskStatusChart"></canvas>
            </div>

            <h3 class="text-center mt-5 text-info">Task Priorities</h3>
            <div class="chart-container">
                <canvas id="taskPriorityChart"></canvas>
            </div>

            <h3 class="text-center mt-5 text-info">Task Due Dates</h3>
            <div class="chart-container">
                <canvas id="taskDueDateChart"></canvas>
            </div>

            <!-- Generate PDF Button -->
            <button type="button" class="btn btn-success w-100 py-2 mt-4 shadow-lg" id="generatePDFButton">Generate PDF</button>
        </div>
                    </div>
                </div>

<!-- Modal Structure for Comparison -->
<div class="modal fade" id="comparisonModal" tabindex="-1" role="dialog" aria-labelledby="comparisonModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="comparisonModalLabel">Compare Reports</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Period Selection -->
                <div class="form-group">
                    <label for="comparisonType">Comparison Type:</label>
                    <select id="comparisonType" class="form-control">
                        <option value="custom">Custom Date Range</option>
                        <option value="month">By Month</option>
                        <option value="week">By Week (Select a Month)</option>
                    </select>
                </div>

                <!-- Custom Date Range Fields -->
                <div id="customRangeFields">
                    <form id="comparisonForm">
                        <!-- First Date Range -->
                        <label for="start_date1">Start Date 1:</label>
                        <input type="date" id="start_date1" name="start_date1" class="form-control">

                        <label for="end_date1">End Date 1:</label>
                        <input type="date" id="end_date1" name="end_date1" class="form-control">

                        <!-- Second Date Range -->
                        <label for="start_date2" class="mt-3">Start Date 2:</label>
                        <input type="date" id="start_date2" name="start_date2" class="form-control">

                        <label for="end_date2">End Date 2:</label>
                        <input type="date" id="end_date2" name="end_date2" class="form-control">
                    </form>
                </div>

                <!-- Predefined Month Selection Fields -->
                <div id="monthRangeFields" class="d-none">
                    <form id="monthForm">
                        <label for="month1">Select Month 1:</label>
                        <select id="month1" class="form-control">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>

                        <label for="month2" class="mt-3">Select Month 2:</label>
                        <select id="month2" class="form-control">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </form>
                </div>

                <!-- Predefined Week Selection Fields -->
                <div id="weekRangeFields" class="d-none">
                    <form id="weekForm">
                        <!-- Select the Month -->
                        <label for="weekMonth">Select Month:</label>
                        <select id="weekMonth" class="form-control">
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>

                        <!-- Select Week 1 -->
                        <label for="week1" class="mt-3">Select Week 1:</label>
                        <select id="week1" class="form-control">
                            <option value="1">Week 1</option>
                            <option value="2">Week 2</option>
                            <option value="3">Week 3</option>
                            <option value="4">Week 4</option>
                            <option value="5">Week 5</option>
                        </select>

                        <!-- Select Week 2 -->
                        <label for="week2" class="mt-3">Select Week 2:</label>
                        <select id="week2" class="form-control">
                            <option value="1">Week 1</option>
                            <option value="2">Week 2</option>
                            <option value="3">Week 3</option>
                            <option value="4">Week 4</option>
                            <option value="5">Week 5</option>
                        </select>
                    </form>
                </div>

                <button type="button" class="btn btn-primary mt-3" id="compareButton">Compare</button>

                <!-- Task Completion Metrics Section -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5>Task Completion Metrics (First Period)</h5>
                        <ul id="completionMetrics1"></ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Task Completion Metrics (Second Period)</h5>
                        <ul id="completionMetrics2"></ul>
                    </div>
                </div>

                <!-- Comparison Charts -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5>Task Status Comparison</h5>
                        <canvas id="taskStatusComparisonChart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h5>Task Priority Comparison</h5>
                        <canvas id="taskPriorityComparisonChart"></canvas>
                    </div>
                    <div class="col-md-6">
                        <h5>Task Due Date Comparison</h5>
                        <canvas id="taskDueDateComparisonChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- end of reports tab -->


<!-- New All Tasks Tab -->
<div class="tab-pane fade" id="tasks" role="tabpanel" aria-labelledby="tasks-tab">
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Team Tasks</h6>
        </div>
        <div class="card-body p-0">
            <!-- Add table-responsive class -->
            <div class="table-responsive">
            <table id="allTeamTasksTable" class="table table-bordered table-hover table-striped table-sm">
            <thead>
                        <tr>
                            <th>Task Name</th>
                            <th>Assigned To</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Duration</th>
                            <th>Approval Status</th>
                            <th>Grade Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($team->tasks as $task)
                            <tr>
                                <td>{{ $task->name }}</td>
                                <td>{{ $task->assignee->name }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $task->priority == 'high' ? '#ff6b6b' : ($task->priority == 'medium' ? '#feca57' : '#1dd1a1') }}; color: white;">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                    </td>
                                <td> 
                                    <span class="badge" style="background-color: {{ $task->status == 'not started' ? '#54a0ff' : ($task->status == 'in progress' ? '#f6b93b' : '#78e08f') }}; color: white;">
                                        {{ ucfirst($task->status) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</td>
                                <td>{{ $task->duration ?? 'N/A' }}</td>
                                <td>
                                    @if($task->approval_status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @elseif($task->approval_status == 'rejected')
                                        <span class="badge badge-danger">Rejected</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($task->is_graded)
                                        <span class="badge badge-success">Graded</span>
                                    @else
                                        <span class="badge badge-warning">Not Graded</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <!-- View Button -->
                                    <a href="{{ route('user.tasks.show', $task->id) }}" class="btn btn-info btn-sm mb-1">View</a>
                                    
                                    @if($task->status == 'done' && $task->approval_status == 'pending' && (Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id))
                                        <!-- Approve Button -->
                                        <form method="POST" action="{{ route('user.tasks.approveOrReject', $task->id) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="approval_status" value="approved">
                                            <button type="submit" class="btn btn-success btn-sm mb-1">Approve</button>
                                        </form>

                                        <!-- Reject Button -->
                                        <button type="button" class="btn btn-danger btn-sm mb-1" data-toggle="modal" data-target="#rejectModalSecondView{{ $task->id }}">
                                            Reject
                                        </button>

                                        <!-- Rejection Modal -->
                                        <div class="modal fade" id="rejectModalSecondView{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabelSecondView{{ $task->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form method="POST" action="{{ route('user.tasks.approveOrReject', $task->id) }}">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="rejectModalLabelSecondView{{ $task->id }}">Reject Task: {{ $task->name }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="rejection_remarks">Rejection Remarks</label>
                                                                <textarea name="rejection_remarks" class="form-control" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="approval_status" value="rejected">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-danger">Submit Rejection</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Show Grade Button if Task is Approved -->
                                    @if($team->has_rewards && $task->approval_status == 'approved' && (Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id))
                                        @if($task->is_graded)
                                            <button class="btn btn-secondary btn-sm mb-1" disabled>Already Graded</button>
                                        @else
                                            <button class="btn btn-primary btn-sm mb-1" data-toggle="modal" data-target="#gradeModalSecondView{{ $task->id }}">
                                                Grade
                                            </button>
                                        @endif
                                    @endif

                                    <!-- Delete Task Button -->
                                    <button class="btn btn-danger btn-sm delete-task mb-1" data-task-id="{{ $task->id }}">Delete</button>
                                </td>

                                <!-- Grade Modal -->
                                @if($task->approval_status == 'approved' && (Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id))
                                    <div class="modal fade" id="gradeModalSecondView{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="gradeModalLabelSecondView{{ $task->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('user.tasks.grade', $task->id) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="gradeModalLabelSecondView{{ $task->id }}">Grade Task: {{ $task->name }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="grade">Select Grade</label>
                                                            <select name="grade" id="grade" class="form-control" required>
                                                                <option value="good">Good</option>
                                                                <option value="very good">Very Good</option>
                                                                <option value="excellent">Excellent</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save Grade</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <!-- End of Grade Modal -->
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End of All Tasks Section -->



                <!-- Chat Tab -->
                <div class="tab-pane fade" id="chat" role="tabpanel">
    <div id="chat-messages" style="height: 340px; overflow-y: scroll; border: 1px solid #ddd; padding: 10px;">
        <!-- Chat messages will be dynamically loaded here -->
    </div>

    <form id="chat-form" class="mt-3" enctype="multipart/form-data">
    @csrf
    <div class="input-group">
        <input type="text" id="message-input" class="form-control" placeholder="Type a message...">
        <div class="input-group-append">
            <!-- Add file input for attachments -->
            <input type="file" id="file-input" class="form-control" name="file" accept=".jpg,.png,.pdf,.doc,.docx">
            <button class="btn btn-primary" type="submit">Send</button>
        </div>
    </div>
</form>
</div>


                <!-- Leaderboard Tab -->
<div class="tab-pane fade" id="leaderboard" role="tabpanel" aria-labelledby="leaderboard-tab">
    <div class="card shadow">
        <!-- Team Name in Large Font with Animated Header -->
        <div class="card-header py-3 d-flex justify-content-center align-items-center">
            <h2 class="font-weight-bold text-primary animate-header">{{ $team->name }} <span class="badge badge-pill badge-warning">Leaderboard</span></h2>
        </div>
        
        <div class="card-body">
            <!-- Responsive Leaderboard Table Container -->
            <div class="table-responsive">
                <table class="table table-bordered gamified-table">
                    <thead>
                        <tr class="table-header">
                            <th><i class="fas fa-trophy"></i> Rank</th>
                            <th><i class="fas fa-user"></i> Player Name</th>
                            <th><i class="fas fa-tasks"></i> Total Tasks Completed</th>
                            <th><i class="fas fa-star"></i> Total Points Earned</th>
                            <th><i class="fas fa-level-up-alt"></i> Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $rank = 1; // Initialize rank
                        @endphp

                        @foreach ($team->members->sortByDesc(function($member) use ($team) {
                            return $member->tasks()->where('team_id', $team->id)->sum('points');
                        }) as $member)
                            <tr class="gamified-row {{ $rank == 1 ? 'top-player' : '' }}"> <!-- Highlight top player -->
                                <!-- Rank with animated crown icon for top player -->
                                <td>
                                    @if($rank == 1)
                                        <i class="fas fa-crown"></i> {{ $rank++ }}
                                    @else
                                        {{ $rank++ }}
                                    @endif
                                </td>

                                <!-- Player Name with Profile Icon -->
                                <td><i class="fas fa-user-circle"></i> {{ $member->name }}</td>

                                <!-- Total Tasks Completed (count tasks for this team) -->
                                <td>{{ $member->tasks()->where('team_id', $team->id)->where('status', 'done')->count() }}</td>

                                <!-- Total Points Earned (sum points for this team) -->
                                <td>
                                    <i class="fas fa-coins"></i> {{ $member->tasks()->where('team_id', $team->id)->sum('points') }}
                                </td>

                                <!-- Player Level with Progress Bar Animation -->
                                <td>
                                    <div class="progress-level">
                                        <div class="level-number">Level {{ $member->level }}</div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: {{ ($member->xp / 100) * 100 }}%;"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> <!-- End table-responsive -->
        </div>
    </div>
</div>
<!-- End of Leaderboard Tab -->



                <!-- Rewards Shop Tab -->
                <div class="tab-pane fade" id="rewards" role="tabpanel" aria-labelledby="rewards-tab">
                    <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
    <h6 class="m-0 font-weight-bold text-primary">Rewards Shop</h6>
    @if(Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id)
        <!-- Add Reward Button -->
        <button type="button" class="btn btn-primary ml-auto" data-toggle="modal" data-target="#addRewardModal">
            Add Reward
        </button>
    @endif
</div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($team->rewards as $reward)
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $reward->name }}</h5>
                                                <p class="card-text">{{ $reward->description }}</p>
                                                <p class="card-text"><strong>Points Required:</strong> {{ $reward->points_required }}</p>
                                            </div>
                                            <div class="card-footer">
                                                @if(Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id)
                                                    <!-- Edit and Delete Actions for Admin/Creator -->
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editRewardModal{{ $reward->id }}">
                                                        Edit
                                                    </button>
                                                    <form method="POST" action="{{ route('rewards.destroy', $reward->id) }}" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this reward?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                @else
                                                    <!-- Redeem Button for Users -->
                                                    @if(Auth::user()->tasks()->where('team_id', $team->id)->sum('points') >= $reward->points_required)
                                                        <form method="POST" action="{{ route('rewards.redeem', $reward->id) }}">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm">Redeem</button>
                                                        </form>
                                                    @else
                                                        <button class="btn btn-secondary btn-sm" disabled>Not Enough Points</button>
                                                    @endif
                                                @endif
                                                </div>
                                        </div>
                                    </div>

                                    <!-- Edit Reward Modal -->
                                    <div class="modal fade" id="editRewardModal{{ $reward->id }}" tabindex="-1" role="dialog" aria-labelledby="editRewardModalLabel{{ $reward->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('rewards.update', $reward->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editRewardModalLabel{{ $reward->id }}">Edit Reward</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="name">Reward Name</label>
                                                            <input type="text" name="name" id="name" class="form-control" value="{{ $reward->name }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="description">Description</label>
                                                            <textarea name="description" id="description" class="form-control" required>{{ $reward->description }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="points_required">Points Required</label>
                                                            <input type="number" name="points_required" id="points_required" class="form-control" value="{{ $reward->points_required }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update Reward</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Edit Reward Modal -->
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Rewards Shop Tab -->
            </div>
        </div>
    </div>

    <!-- Add Reward Modal -->
    @if(Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id)
        <div class="modal fade" id="addRewardModal" tabindex="-1" role="dialog" aria-labelledby="addRewardModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('rewards.store') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addRewardModalLabel">Add Reward</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Reward Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="points_required">Points Required</label>
                                <input type="number" name="points_required" id="points_required" class="form-control" required>
                            </div>
                            <input type="hidden" name="team_id" value="{{ $team->id }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Reward</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    @endif
    <!-- End of Add Reward Modal -->

</x-app-layout>
<style>
/* Responsive Table: Ensure horizontal scrolling on mobile */
.table-responsive {
    overflow-x: auto;
}

/* Team Name Animation */
.animate-header {
    animation: float-header 3s infinite ease-in-out;
}

/* Leaderboard Table Styling */
.gamified-table {
    width: 100%;
    background-color: #f8f9fa;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

/* Table Header Styling */
.table-header th {
    background-color: #343a40;
    color: #fff;
    font-weight: bold;
    padding: 15px;
    text-align: center;
    font-size: 1.2rem;
}

/* Table Row Styling */
.gamified-row {
    text-align: center;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.gamified-row:hover {
    background-color: #f1f3f5;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transform: translateY(-3px);
}

/* Highlight the Top Player */
.top-player {
    background-color: #ffd700;
    font-weight: bold;
    color: #212529;
}

/* Progress Bar for Level Display */
.progress-level {
    text-align: center;
}

.level-number {
    font-weight: bold;
    margin-bottom: 5px;
}

.progress {
    background-color: #e9ecef;
    border-radius: 10px;
    height: 20px;
    overflow: hidden;
    width: 100px;
    margin: 0 auto;
}

.progress-bar {
    background-color: #28a745;
    height: 100%;
    animation: grow-bar 2s ease;
}

/* Icon Animations */
.fas {
    margin-right: 5px;
}

/* Animation for Floating Header */
@keyframes float-header {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

/* Animation for Progress Bar Growth */
@keyframes grow-bar {
    from {
        width: 0;
    }
    to {
        width: 100%;
    }
}

/* Animation for Hovering Rows */
.gamified-row:hover {
    transition: 0.4s;
    transform: scale(1.03);
}

/* Media Query for Mobile Devices */
@media (max-width: 768px) {
    .table-header th, .gamified-row td {
        font-size: 0.9rem; /* Adjust font size for mobile */
    }
    
    .progress-level .level-number {
        font-size: 0.8rem; /* Smaller level number for mobile */
    }

    .progress {
        width: 80px; /* Adjust progress bar width for mobile */
    }
}

/* Media Query for Smaller Mobile Screens */
@media (max-width: 480px) {
    .table-header th, .gamified-row td {
        font-size: 0.8rem; /* Further reduce font size */
        padding: 10px; /* Adjust padding */
    }

    .progress {
        width: 60px; /* Further adjust progress bar width for small screens */
    }
}
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        // Automatically close the alert after 5 seconds
        setTimeout(function() {
            $(".alert").alert('close');
        }, 5000); // Auto-close after 5 seconds
    });
</script>
<script>
    // Listen for errors on the page
    window.addEventListener('error', function(event) {
        console.error('Error caught:', event.message, 'on', event.filename, 'at line', event.lineno);
    });
</script>
<script>
    $(document).ready(function() {
        console.log('jQuery and Bootstrap are loaded');
        $('.alert').alert();  // Check if the alert functionality works.
    });
</script>
<script>
    
    $(document).ready(function() {
        $('#add-checklist-item').click(function() {
            var checklistItem = `
                <div class="input-group mb-2">
                    <input type="text" name="checklists[]" class="form-control" placeholder="Checklist item">
                    <div class="input-group-append">
                        <button class="btn btn-danger remove-checklist-item" type="button">Remove</button>
                    </div>
                </div>`;
            $('#checklist-items').append(checklistItem);
        });

        $(document).on('click', '.remove-checklist-item', function() {
            $(this).closest('.input-group').remove();
        });
    });

    $(document).ready(function () {
    var authUserId = {{ Auth::id() }};
    var teamId = {{ $team->id }};

    function fetchMessages() {
        // Check if #chat-messages exists before attempting to manipulate it
        if ($('#chat-messages').length) {
            $.get('/teams/' + teamId + '/messages', function (messages) {
                $('#chat-messages').empty();

                messages.forEach(function (message) {
                    var isCurrentUser = message.user_id == authUserId;
                    var profilePhoto = message.user.profile_photo_path 
                        ? '{{ asset('storage') }}' + '/' + message.user.profile_photo_path 
                        : '{{ asset('dist/img/avatar5.png') }}';

                    var messageTimestamp = new Date(message.created_at).toLocaleString('en-US', {
                        hour: 'numeric',
                        minute: 'numeric',
                        hour12: true,
                        month: 'short',
                        day: 'numeric'
                    });

                    var fileElement = '';

                    if (message.file_url) {
    var fileExtension = message.file_url.split('.').pop().toLowerCase();
    var filename = message.original_file_name || message.file_url.split('/').pop(); // Use original name if available
    var encodedFilename = encodeURIComponent(filename); // URL encode the filename

    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
        fileElement = `
            <div class="file-preview">
                <img src="${message.file_url}" alt="Image Preview" class="img-thumbnail" style="max-width: 150px;" />
                <a href="/download/${encodedFilename}" class="d-block mt-2">Download ${filename}</a>
            </div>
        `;
    } else if (fileExtension === 'pdf') {
        fileElement = `
            <a href="/download/${encodedFilename}" class="d-block mt-2">
                <i class="fas fa-file-pdf"></i> Download PDF (${filename})
            </a>
        `;
    } else if (['doc', 'docx'].includes(fileExtension)) {
        fileElement = `
            <a href="/download/${encodedFilename}" class="d-block mt-2">
                <i class="fas fa-file-word"></i> Download Word Document (${filename})
            </a>
        `;
    } else {
        fileElement = `
            <a href="/download/${encodedFilename}" class="d-block mt-2">Download ${filename}</a>
        `;
    }
}


                    // Create the message box with file preview/icon
                    var messageBox = isCurrentUser ? `
                        <div class="media mb-3 current-user-message">
                            <div class="media-body bg-primary text-white ml-3" style="border-radius: 10px; padding: 10px; display: inline-block; max-width: 60%;">
                                <h6 class="mt-0 text-right">${message.user.name}</h6>
                                <p>${message.message}</p>
                                ${fileElement}
                                <small class="text-light text-right d-block">${messageTimestamp}</small>
                            </div>
                            <img src="${profilePhoto}" class="rounded-circle" alt="User Image" style="width: 50px; height: 50px; margin-left: 10px;">
                        </div>
                    ` : `
                        <div class="media mb-3 other-user-message">
                            <img src="${profilePhoto}" class="rounded-circle" alt="User Image" style="width: 50px; height: 50px; margin-right: 10px;">
                            <div class="media-body bg-light-blue text-dark" style="border-radius: 10px; padding: 10px; display: inline-block; max-width: 60%;">
                                <h6 class="mt-0">${message.user.name}</h6>
                                <p>${message.message}</p>
                                ${fileElement}
                                <small class="text-muted">${messageTimestamp}</small>
                            </div>
                        </div>
                    `;

                    $('#chat-messages').append(messageBox);
                });

                // Scroll to the bottom of the chat after loading messages
                scrollToBottom();
            });
        } else {
            console.error("Node #chat-messages not found in the DOM.");
        }
    }

    // Scroll to the bottom of the chat window
    function scrollToBottom() {
        var chatMessages = $('#chat-messages');
        if (chatMessages.length) {
            chatMessages.scrollTop(chatMessages[0].scrollHeight);
        }
    }

    // Auto-refresh messages every 5 seconds
    setInterval(fetchMessages, 5000);







// Function to scroll the chat window to the bottom
function scrollToBottom() {
    var chatMessages = $('#chat-messages');
    chatMessages.scrollTop(chatMessages[0].scrollHeight);
}

// Fetch messages initially
fetchMessages();

// Post new message
$('#chat-form').submit(function (e) {
    e.preventDefault();

    var formData = new FormData();
    var message = $('#message-input').val();
    var file = $('#file-input')[0].files[0];  // Get the selected file

    if (message.trim() !== '' || file) {
        formData.append('message', message);  // Append message text
        formData.append('_token', $('input[name="_token"]').val());  // CSRF token

        if (file) {
            formData.append('file', file);  // Append the file to the request
        }

        console.log('FormData:', formData);  // Log FormData to check if file is being appended

        // Send the form data using AJAX
        $.ajax({
            url: '/teams/' + teamId + '/messages',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#message-input').val('');  // Clear input field
                $('#file-input').val('');  // Clear file input
                fetchMessages();  // Reload chat messages
            },
            error: function (xhr, status, error) {
                alert('Error sending message: ' + xhr.responseText);
                console.error(xhr.responseText);
            }
        });
    }
});

// Auto-refresh messages every 5 seconds
setInterval(fetchMessages, 5000);
    });
</script>
<script>
    // Always define userId globally
    var userId = {{ auth()->user()->id ?? 'null' }};
    
    // Conditionally define teamId if it's available
    @if (isset($team))
        var teamId = {{ $team->id }};
    @else
        var teamId = null;  // Set teamId to null if not available
    @endif
</script>

<script>
$(document).ready(function () {
    // Event listener for clicking the delete button
    $('.delete-task').on('click', function (e) {
        e.preventDefault(); // Prevent default action

        var taskId = $(this).data('task-id'); // Get task ID from data attribute
        var taskRow = $(this).closest('tr');  // Find the row to remove

        if (confirm('Are you sure you want to delete this task?')) {
            // Send the DELETE request using AJAX
            $.ajax({
                url: '/tasks/' + taskId, // Adjust this to your correct route
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token is included
                },
                success: function (response) {
                    alert('Task deleted successfully.');

                    // Remove the task from the table or list
                    taskRow.remove();  // This removes the task row from the view
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText); // Log the error for debugging
                    alert('Error deleting task: ' + xhr.responseText);
                }
            });
        }
    });
});


let taskStatusChart, taskPriorityChart, taskDueDateChart, filteredData = null;

function renderCharts(chartType, data) {
    // Destroy previous charts if they exist
    if (taskStatusChart) taskStatusChart.destroy();
    if (taskPriorityChart) taskPriorityChart.destroy();
    if (taskDueDateChart) taskDueDateChart.destroy();

    // Task Status Chart
    const ctxStatus = document.getElementById('taskStatusChart').getContext('2d');
    taskStatusChart = new Chart(ctxStatus, {
        type: chartType,
        data: {
            labels: ['Not Started', 'In Progress', 'Done'],
            datasets: [{
                label: 'Task Status',
                data: [data.notStarted, data.inProgress, data.done],
                backgroundColor: ['#54a0ff', '#f6b93b', '#78e08f'],  // Keep dot colors

borderColor: '#000000',  // Set line color to black

borderWidth: 1,

pointBackgroundColor: ['#54a0ff', '#f6b93b', '#78e08f'],  // Keep dot colors

pointBorderColor: '#000000',  // Set point border to black for clarity

fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        generateLabels: function(chart) {
                            return chart.data.datasets[0].data.map(function(value, index) {
                                return {
                                    text: chart.data.labels[index],
                                    fillStyle: chart.data.datasets[0].backgroundColor[index]
                                };
                            });
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Task Priority Chart
    const ctxPriority = document.getElementById('taskPriorityChart').getContext('2d');
    taskPriorityChart = new Chart(ctxPriority, {
        type: chartType,
        data: {
            labels: ['Low', 'Medium', 'High'],
            datasets: [{
                label: 'Task Priority',
                data: [data.lowPriority, data.mediumPriority, data.highPriority],
                backgroundColor: ['#ff6b6b', '#feca57', '#1dd1a1'],  // Keep dot colors

borderColor: '#000000',  // Set line color to black

borderWidth: 1,

pointBackgroundColor: ['#ff6b6b', '#feca57', '#1dd1a1'],  // Keep dot colors

pointBorderColor: '#000000',  // Set point border to black for clarity

fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        generateLabels: function(chart) {
                            return chart.data.datasets[0].data.map(function(value, index) {
                                return {
                                    text: chart.data.labels[index],
                                    fillStyle: chart.data.datasets[0].backgroundColor[index]
                                };
                            });
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Task Due Date Chart
    const ctxDueDate = document.getElementById('taskDueDateChart').getContext('2d');
    taskDueDateChart = new Chart(ctxDueDate, {
        type: chartType,
        data: {
            labels: ['Overdue', 'Due This Week', 'Due Today'],
            datasets: [{
                label: 'Task Due Dates',
                data: [data.overdue, data.dueThisWeek, data.dueToday],
                backgroundColor: ['#d32f2f', '#f57c00', '#388e3c'],  // Keep dot colors

                borderColor: '#000000',  // Set line color to black

                borderWidth: 1,

                pointBackgroundColor: ['#d32f2f', '#f57c00', '#388e3c'],  // Keep dot colors

                pointBorderColor: '#000000',  // Set point border to black for clarity

                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        generateLabels: function(chart) {
                            return chart.data.datasets[0].data.map(function(value, index) {
                                return {
                                    text: chart.data.labels[index],
                                    fillStyle: chart.data.datasets[0].backgroundColor[index]
                                };
                            });
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Fetch and render default data on page load
function fetchDefaultData() {
    const teamId = "{{ $team->id }}"; // Use the team ID from the view

    fetch(`/reports/${teamId}/generate`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        filteredData = data;
        renderCharts('pie', data); // Default chart type is 'pie'
    })
    .catch(error => console.error('Error fetching default data:', error));
}

// Fetch filtered data based on user-selected date range
function fetchFilteredData() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const chartType = document.getElementById('chartType').value;  // e.g., pie, bar, line
    const teamId = "{{ $team->id }}"; // Use the team ID from the view

    if (startDate && endDate) {
        // Fetch the filtered data based on the selected date range
        fetch(`/reports/${teamId}/generate?start_date=${startDate}&end_date=${endDate}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            // Re-render the charts with the new filtered data
            filteredData = data;
            renderCharts(chartType, data);  // Pass the new data to renderCharts
        })
        .catch(error => console.error('Error fetching filtered data:', error));
    } else {
        // Handle case when no date is selected, and use existing data if available
        console.warn('Please select both a start and end date');
        renderCharts(chartType, filteredData || {
            notStarted: 0,
            inProgress: 0,
            done: 0,
            lowPriority: 0,
            mediumPriority: 0,
            highPriority: 0,
            overdue: 0,
            dueThisWeek: 0,
            dueToday: 0
        });
    }
}


// Initialize default data and charts on page load
document.addEventListener('DOMContentLoaded', () => {
    fetchDefaultData(); // Load default data

    // Listen to chart type change event
    document.getElementById('chartType').addEventListener('change', () => {
        fetchFilteredData(); // Re-render chart based on selected chart type
    });

    // Listen to the filter button click event
    document.getElementById('filterButton').addEventListener('click', fetchFilteredData);
});

document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('lineChart').getContext('2d');
            
            const lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['0', '0.4', '0.6', '1', '1.2', '1.4', '2', '2.2', '2.6', '3', '3.4', '4.2', '5', '5.4', '6'],
                    datasets: [
                        {
                            label: 'Performance',
                            data: [20, 40, 35, 60, 70, 65, 50, 55, 80, 90, 75, 100, 85, 50, 100],
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0,123,255,0.1)',
                            borderWidth: 2,
                            pointRadius: 4,
                            pointBackgroundColor: '#007bff',
                            fill: true,
                            tension: 0.4 // Smooth lines
                        },
                        {
                            label: 'Target',
                            data: [60, 55, 50, 45, 60, 58, 55, 60, 65, 60, 55, 62, 63, 58, 55],
                            borderColor: '#6c757d',
                            backgroundColor: 'rgba(108,117,125,0.1)',
                            borderWidth: 2,
                            pointRadius: 4,
                            pointBackgroundColor: '#6c757d',
                            fill: true,
                            tension: 0.4 // Smooth lines
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                display: true,
                                color: 'rgba(108,117,125,0.2)',
                            },
                            ticks: {
                                font: {
                                    family: 'Poppins',
                                    size: 12
                                },
                                color: '#6c757d'
                            }
                        },
                        y: {
                            grid: {
                                display: true,
                                color: 'rgba(108,117,125,0.2)',
                            },
                            ticks: {
                                font: {
                                    family: 'Poppins',
                                    size: 12
                                },
                                color: '#6c757d',
                                beginAtZero: true,
                                max: 100
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: {
                                    family: 'Poppins',
                                    size: 14
                                },
                                color: '#495057'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.7)',
                            titleFont: {
                                family: 'Poppins',
                                size: 14
                            },
                            bodyFont: {
                                family: 'Poppins',
                                size: 12
                            },
                            cornerRadius: 4,
                            padding: 12
                        }
                    },
                    elements: {
                        line: {
                            tension: 0.4 // Controls the line smoothness
                        },
                        point: {
                            radius: 4, // Point size
                            backgroundColor: '#007bff'
                        }
                    }
                }
            });
        });
// Generate PDF functionality
// Generate PDF functionality
document.getElementById('generatePDFButton').addEventListener('click', function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Fallback to filteredData or predefined values if filteredData is not available
    const dataToUse = filteredData || {
        notStarted: {{ $notStarted }},
        inProgress: {{ $inProgress }},
        done: {{ $done }},
        lowPriority: {{ $lowPriority }},
        mediumPriority: {{ $mediumPriority }},
        highPriority: {{ $highPriority }},
        overdue: {{ $overdue }},
        dueThisWeek: {{ $dueThisWeek }},
        dueToday: {{ $dueToday }}
    };

    // Function to format the date in "Month Day, Year" format
    function formatDateString(dateString) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        const date = new Date(dateString);
        return date.toLocaleDateString(undefined, options);
    }

    // Helper function to draw a rounded rectangle
    function drawRoundedRect(x, y, width, height, radius) {
        doc.roundedRect(x, y, width, height, radius, radius, 'S');
    }
    
    // Get the current date and format it
    const currentDate = formatDateString(new Date());

    // Title and formatted generation date
    doc.setFontSize(18);
    doc.text(`Team Report for {{ $team->name }}`, 10, 10); // Include team name in the title
    doc.setFontSize(12);
    doc.text(`Generated on: ${currentDate}`, 10, 20);  // Use the formatted date here

    // Task Status Summary with labels
    doc.setFontSize(14);
    doc.text('Task Status Summary', 10, 30);
    doc.setFontSize(12);
    doc.text(`• Not Started: ${dataToUse.notStarted}`, 10, 40);
    doc.text(`• In Progress: ${dataToUse.inProgress}`, 10, 50);
    doc.text(`• Done: ${dataToUse.done}`, 10, 60);

    // Function to calculate aspect ratio based on desired width
function calculateAspectRatio(originalWidth, originalHeight, desiredWidth) {
    return (originalHeight / originalWidth) * desiredWidth;
}

    // Add Task Statuses chart image
    const taskStatusChart = document.getElementById('taskStatusChart');
if (taskStatusChart) {
    const imgWidth = 180; // Set the width you desire
    const imgHeight = calculateAspectRatio(taskStatusChart.width, taskStatusChart.height, imgWidth);
    doc.addImage(taskStatusChart.toDataURL('image/png'), 'PNG', 10, 70, imgWidth, imgHeight); // Adjust position as needed
}
   // Add new page for Task Priorities
   doc.addPage(); 
    doc.setFontSize(14);
    doc.text('Task Priorities Summary', 10, 20);
    doc.setFontSize(12);
    doc.text(`• Low Priority: ${dataToUse.lowPriority}`, 10, 30);
    doc.text(`• Medium Priority: ${dataToUse.mediumPriority}`, 10, 40);
    doc.text(`• High Priority: ${dataToUse.highPriority}`, 10, 50);

    // Add Task Priorities chart image
    const taskPriorityChart = document.getElementById('taskPriorityChart');
if (taskPriorityChart) {
    const imgWidth = 180; // Set the width you desire
    const imgHeight = calculateAspectRatio(taskPriorityChart.width, taskPriorityChart.height, imgWidth);
    doc.addImage(taskPriorityChart.toDataURL('image/png'), 'PNG', 10, 60, imgWidth, imgHeight); // Adjust position as needed
}

    // Add new page for Task Due Dates
    doc.addPage(); 
    doc.setFontSize(14);
    doc.text('Task Due Date Summary', 10, 20);
    doc.setFontSize(12);
    doc.text(`• Overdue: ${dataToUse.overdue}`, 10, 30);
    doc.text(`• Due This Week: ${dataToUse.dueThisWeek}`, 10, 40);
    doc.text(`• Due Today: ${dataToUse.dueToday}`, 10, 50);

    // Add Task Due Dates chart image
    const taskDueDateChart = document.getElementById('taskDueDateChart');
    if (taskDueDateChart) {
        const imgWidth = 180; // Set the width you desire
        const imgHeight = calculateAspectRatio(taskDueDateChart.width, taskDueDateChart.height, imgWidth);
        doc.addImage(taskDueDateChart.toDataURL('image/png'), 'PNG', 10, 60, imgWidth, imgHeight); // Adjust position as needed
    }
    // Add new page for Task Completion Metrics
     // PDF Header Section
    doc.addPage(); 
    doc.setFontSize(18);
    doc.setTextColor(31, 78, 121);
    doc.text('Task Completion Metrics', 10, 15);

    // Section 1: Task Completion Rate
    drawRoundedRect(10, 20, 90, 60, 5);
    doc.setFontSize(14);
    doc.setTextColor(0);
    doc.text('Task Completion Rate', 15, 30);
    doc.setFontSize(12);
    
    let yPosition = 40;
    @foreach($team->members as $member)
        @php
            $totalTasks = $team->tasks()->where('user_id', $member->id)->count();
            $completedTasks = $team->tasks()->where('user_id', $member->id)->where('status', 'done')->count();
            $completionRate = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
        @endphp
        doc.text(`• {{ $member->name }}: {{ round($completionRate, 2) }}%`, 15, yPosition);
        yPosition += 10;
    @endforeach

    // Section 2: Overdue Tasks
    drawRoundedRect(110, 20, 90, 60, 5);
    doc.setFontSize(14);
    doc.setTextColor(194, 24, 7); // Red color for 'Overdue Tasks'
    doc.text('Overdue Tasks', 115, 30);
    doc.setFontSize(12);

    yPosition = 40;
    @foreach($team->tasks()->where('status', '!=', 'done')->where('due_date', '<', now())->get() as $task)
        doc.text(`• {{ $task->name }} (Due: {{ $task->due_date->format('M d, Y') }})`, 115, yPosition);
        yPosition += 10;
    @endforeach

    // Section 3: On-time Task Delivery
    drawRoundedRect(10, 90, 190, 60, 5);
    doc.setFontSize(14);
    doc.setTextColor(34, 139, 34); // Green color for 'On-time Task Delivery'
    doc.text('On-time Task Delivery', 15, 100);
    doc.setFontSize(12);

    yPosition = 110;
    @foreach($team->tasks()->where('status', 'done')->get() as $task)
        @if($task->due_date && $task->date_completed && $task->due_date >= $task->date_completed)
            doc.text(`• {{ $task->name }} (Completed: {{ $task->date_completed->format('M d, Y') }})`, 15, yPosition);
            yPosition += 10;
        @endif
    @endforeach

    // New Page for Task Metrics Row 2
    doc.addPage();
    doc.setFontSize(14);
    doc.text('Task Metrics', 10, 15);
    doc.setFontSize(12);

    // Section 4: Tasks Assigned per Member
    drawRoundedRect(10, 20, 90, 60, 5);
    doc.text('Tasks Assigned per Member', 15, 30);
    
    yPosition = 40;
    @foreach($team->members as $member)
        doc.text(`• {{ $member->name }}: {{ $team->tasks()->where('user_id', $member->id)->count() }} tasks`, 15, yPosition);
        yPosition += 10;
    @endforeach

    // Section 5: Top Performers
    drawRoundedRect(110, 20, 90, 60, 5);
    doc.setTextColor(0, 102, 204); // Blue color for 'Top Performers'
    doc.text('Top Performers', 115, 30);
    doc.setFontSize(12);

    yPosition = 40;
    @foreach($topPerformers as $performer)
        doc.text(`• {{ $performer->name }}: {{ $performer->completed_tasks }} tasks`, 115, yPosition);
        yPosition += 10;
    @endforeach

    // Section 6: Top Scorers (if rewards exist)
    @if($team->has_rewards)
    drawRoundedRect(10, 90, 190, 60, 5);
    doc.setTextColor(255, 165, 0); // Orange color for 'Top Scorers'
    doc.text('Top Scorers', 15, 100);
    
    yPosition = 110;
    @foreach($topScorers as $scorer)
        doc.text(`• {{ $scorer->name }}: {{ $scorer->total_points !== null ? $scorer->total_points . ' points' : '0 points' }} points`, 15, yPosition);
        yPosition += 10;
    @endforeach
    @endif

    // Add new page for Task Metrics Row 3
    doc.addPage();
    doc.setFontSize(14);
    doc.setTextColor(0, 174, 199); // RGB values for a cyan/turquoise color
    doc.text('Average Task Completion Time', 10, 15);
    doc.setFontSize(12);

    // Draw rounded rectangle around the Average Task Completion Time section
    drawRoundedRect(10, 20, 190, 60, 5);

    yPosition = 30;
    @foreach($team->members as $member)
        doc.text(`• {{ $member->name }}: {{ $averageTime ? round($averageTime, 2) . ' hours' : 'N/A' }}`, 15, yPosition);
        yPosition += 10;
    @endforeach

 

    // Save the PDF
    doc.save(`team-report-${new Date().toISOString()}.pdf`);
});



$(document).ready(function() {
    let taskStatusComparisonChart, taskPriorityComparisonChart, taskDueDateComparisonChart;

    // Toggle visibility of date range or predefined period selection
    $('#comparisonType').on('change', function () {
        const comparisonType = $(this).val();
        if (comparisonType === 'custom') {
            $('#customRangeFields').show();
            $('#monthRangeFields').addClass('d-none');
            $('#weekRangeFields').addClass('d-none');
        } else if (comparisonType === 'month') {
            $('#customRangeFields').hide();
            $('#monthRangeFields').removeClass('d-none');
            $('#weekRangeFields').addClass('d-none');
        } else if (comparisonType === 'week') {
            $('#customRangeFields').hide();
            $('#monthRangeFields').addClass('d-none');
            $('#weekRangeFields').removeClass('d-none');
        }
    });

    // Event listener for the compare button inside the modal
    $('#compareButton').on('click', function () {
        const comparisonType = $('#comparisonType').val();

        let label1 = '', label2 = ''; // Labels for range 1 and range 2

        if (comparisonType === 'custom') {
            const startDate1 = $('#start_date1').val();
            const endDate1 = $('#end_date1').val();
            const startDate2 = $('#start_date2').val();
            const endDate2 = $('#end_date2').val();

            label1 = `${startDate1} to ${endDate1}`;
            label2 = `${startDate2} to ${endDate2}`;

            if (!startDate1 || !endDate1 || !startDate2 || !endDate2) {
                alert('Please select both date ranges.');
                return;
            }
            // Fetch data for custom date range
            fetchComparisonData(startDate1, endDate1, startDate2, endDate2, label1, label2);
        } else if (comparisonType === 'month') {
            const month1 = $('#month1').val();
            const month2 = $('#month2').val();

            label1 = `Month ${month1}`;
            label2 = `Month ${month2}`;

            const startDate1 = `2024-${month1}-01`;  // Start of selected month 1
            const endDate1 = new Date(2024, parseInt(month1), 0).toISOString().split('T')[0];  // End of month 1
            const startDate2 = `2024-${month2}-01`;  // Start of selected month 2
            const endDate2 = new Date(2024, parseInt(month2), 0).toISOString().split('T')[0];  // End of month 2

            // Fetch data for the selected months
            fetchComparisonData(startDate1, endDate1, startDate2, endDate2, label1, label2);
        } else if (comparisonType === 'week') {
            const selectedMonth = $('#weekMonth').val();
            const week1 = $('#week1').val();
            const week2 = $('#week2').val();

            label1 = `Week ${week1} of Month ${selectedMonth}`;
            label2 = `Week ${week2} of Month ${selectedMonth}`;

            // Calculate start and end dates for Week 1
            const startDate1 = calculateWeekStartDate(selectedMonth, week1);
            const endDate1 = new Date(new Date(startDate1).getTime() + 6 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

            // Calculate start and end dates for Week 2
            const startDate2 = calculateWeekStartDate(selectedMonth, week2);
            const endDate2 = new Date(new Date(startDate2).getTime() + 6 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

            // Fetch data for the selected weeks
            fetchComparisonData(startDate1, endDate1, startDate2, endDate2, label1, label2);
        }
    });

    // Function to calculate the start date of the week based on the month and week number
    function calculateWeekStartDate(month, week) {
        // Get the start date of the month
        const startDate = new Date(`2024-${month}-01`);
        
        // Add days to calculate the start date of the selected week
        const weekStartDate = new Date(startDate.getTime() + (week - 1) * 7 * 24 * 60 * 60 * 1000);
        return weekStartDate.toISOString().split('T')[0];
    }

    // Function to fetch comparison data
    function fetchComparisonData(startDate1, endDate1, startDate2, endDate2, label1, label2) {
        const teamId = "{{ $team->id }}";  // Use your team ID

        // First date range
        $.get(`/reports/${teamId}/generate?start_date=${startDate1}&end_date=${endDate1}`, function (data1) {
            renderMetrics('#completionMetrics1', data1);  // Render first range metrics

            // Second date range
            $.get(`/reports/${teamId}/generate?start_date=${startDate2}&end_date=${endDate2}`, function (data2) {
                renderMetrics('#completionMetrics2', data2);  // Render second range metrics
                renderComparisonCharts(data1, data2, label1, label2);  // Render comparison charts as bar charts
            });
        });
    }

    // Function to render completion metrics
    function renderMetrics(selector, data) {
        const metricsHTML = `
            <li>Not Started: ${data.notStarted}</li>
            <li>In Progress: ${data.inProgress}</li>
            <li>Done: ${data.done}</li>
            <li>Low Priority: ${data.lowPriority}</li>
            <li>Medium Priority: ${data.mediumPriority}</li>
            <li>High Priority: ${data.highPriority}</li>
            <li>Overdue: ${data.overdue}</li>
            <li>Due This Week: ${data.dueThisWeek}</li>
            <li>Due Today: ${data.dueToday}</li>
        `;
        $(selector).html(metricsHTML);
    }

    // Function to render comparison charts (always as bar charts)
    function renderComparisonCharts(data1, data2, label1, label2) {
        // Destroy previous charts if they exist
        if (taskStatusComparisonChart) taskStatusComparisonChart.destroy();
        if (taskPriorityComparisonChart) taskPriorityComparisonChart.destroy();
        if (taskDueDateComparisonChart) taskDueDateComparisonChart.destroy();

        // Task Status Comparison Chart
        const ctxStatus = document.getElementById('taskStatusComparisonChart').getContext('2d');
        taskStatusComparisonChart = new Chart(ctxStatus, {
            type: 'bar',  // Always use bar chart
            data: {
                labels: ['Not Started', 'In Progress', 'Done'],
                datasets: [
                    { label: label1, data: [data1.notStarted, data1.inProgress, data1.done], backgroundColor: '#36a2eb' },
                    { label: label2, data: [data2.notStarted, data2.inProgress, data2.done], backgroundColor: '#ff6384' }
                ]
            },
            options: { responsive: true }
        });

        // Task Priority Comparison Chart
        const ctxPriority = document.getElementById('taskPriorityComparisonChart').getContext('2d');
        taskPriorityComparisonChart = new Chart(ctxPriority, {
            type: 'bar',  // Always use bar chart
            data: {
                labels: ['Low', 'Medium', 'High'],
                datasets: [
                    { label: label1, data: [data1.lowPriority, data1.mediumPriority, data1.highPriority], backgroundColor: '#36a2eb' },
                    { label: label2, data: [data2.lowPriority, data2.mediumPriority, data2.highPriority], backgroundColor: '#ff6384' }
                ]
            },
            options: { responsive: true }
        });

        // Task Due Date Comparison Chart
        const ctxDueDate = document.getElementById('taskDueDateComparisonChart').getContext('2d');
        taskDueDateComparisonChart = new Chart(ctxDueDate, {
            type: 'bar',  // Always use bar chart
            data: {
                labels: ['Overdue', 'Due This Week', 'Due Today'],
                datasets: [
                    { label: label1, data: [data1.overdue, data1.dueThisWeek, data1.dueToday], backgroundColor: '#36a2eb' },
                    { label: label2, data: [data2.overdue, data2.dueThisWeek, data2.dueToday], backgroundColor: '#ff6384' }
                ]
            },
            options: { responsive: true }
        });
    }
});


</script>
<script>
    $(document).ready(function() {
    // Initialize DataTables for the tasks table
    $('#allTeamTasksTable').DataTable({
        "paging": true,        // Enable pagination
        "pageLength": 10,      // Show 10 entries per page
        "lengthChange": true,  // Allow users to change the number of entries per page
        "searching": true,     // Enable search filter
        "ordering": true,      // Enable column sorting
        "info": true,          // Show info about the number of entries
        "autoWidth": false,    // Disable automatic column width calculation
        "language": {
            "paginate": {
                "previous": "<",
                "next": ">"
            }
        }
    });
});

</script>
