<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- Parent container to hold both session duration and reset button -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        @if ($latestSession)
            <p class="text-lg font-semibold text-gray-500">
                Session Duration:
                <span class="text-sm text-gray-500">
                    @if ($latestSession->logout_time === null)
                        <!-- If the session is still active, display a live updating timer -->
                        <span id="session-timer">Calculating...</span>
                    @else
                        <!-- If the session has ended, display the total duration -->
                        ({{ gmdate('H:i:s', $latestSession->duration_in_minutes * 60) }})
                    @endif
                </span>
            </p>
        @endif

       
    </div>


    </x-slot>


@php
    $user = Auth::user();
@endphp

    <div class="row sortable-dashboard" id="dashboard-container">

            <!-- Add Note Widget Button -->
            <div class="col-12 d-flex justify-content-start mb-3">
                <div>
                    <button id="add-note-widget" class="btn btn-primary">Add Note Widget</button>
                </div>

            <div class="ms-2">
                <button id="show-matrix-modal" class="btn btn-primary" data-toggle="modal" data-target="#eisenhowerMatrixModal">
                    View Eisenhower Matrix
                </button>
            </div>
            <div class="ms-2">
            <!-- Add Checklist Widget Button -->
            <button id="add-checklist-widget" class="btn btn-primary">Add Checklist Widget</button>
            </div>
            </div>


<!-- Notes Widgets Container -->
<div id="notes-container" class="col-12 flex-container">
    @foreach ($widgets as $widget)
        @if ($widget->type == 'note')
            <div class="col-12 col-sm-6 col-md-3 sortable-item mb-3" id="widget-{{ $widget->id }}">
                <div class="card shadow h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Note</h5>
                        <div class="widget-actions ms-auto">
                            <!-- Minimize button -->
                            <button type="button" class="btn btn-sm btn-secondary minimize-note-widget" data-widget-id="{{ $widget->id }}" aria-label="Minimize">
                                <span aria-hidden="true">−</span>
                            </button>
                            <!-- Close button -->
                            <button type="button" class="close remove-note-widget" aria-label="Close" data-widget-id="{{ $widget->id }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body note-content" id="note-content-{{ $widget->id }}">
                        <!-- Textarea for adding new notes -->
                        <textarea class="form-control mb-2 note-input" placeholder="Enter a note..."></textarea>
                        <div class="d-flex justify-content-between">
                            <button class="btn btn-success save-note-btn" data-widget-id="{{ $widget->id }}">Add</button>
                        </div>
                        <!-- List of saved notes with delete buttons (properly styled with bullets) -->
                        <ul class="note-list mt-3" id="note-list-{{ $widget->id }}">
                            @foreach ($widget->notes as $note)
                                <li class="d-flex justify-content-between align-items-center mb-2" style="list-style-type: disc;">
                                    <span>{{ $note->content }}</span>
                                    <button class="btn btn-sm btn-light delete-note-item-btn" data-note-id="{{ $note->id }}" aria-label="Delete">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>

<!-- Checklist Widgets Container -->
<div id="checklist-container" class="col-12 flex-container">
    @foreach ($widgets as $widget)
        @if ($widget->type == 'checklist')
            <div class="col-12 col-sm-6 col-md-3 sortable-item mb-3" id="widget-{{ $widget->id }}">
                <div class="card shadow h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Checklist</h5>
                        <div class="widget-actions ms-auto">
                            <!-- Minimize button -->
                            <button type="button" class="btn btn-sm btn-secondary minimize-checklist-widget" data-widget-id="{{ $widget->id }}" aria-label="Minimize">
                                <span aria-hidden="true">−</span>
                            </button>
                            <!-- Close button -->
                            <button type="button" class="close remove-checklist-widget" aria-label="Close" data-widget-id="{{ $widget->id }}">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body checklist-content" id="checklist-content-{{ $widget->id }}">
                        <input type="text" class="form-control mb-2 checklist-input" placeholder="Add checklist item">
                        <button class="btn btn-primary add-checklist-item-btn" data-widget-id="{{ $widget->id }}">Add Item</button>
                        <ul class="checklist-list mt-3" id="checklist-list-{{ $widget->id }}">
                            @foreach ($widget->checklists as $item)
                                <li class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <!-- Checkbox for marking item complete/incomplete -->
                                        <input type="checkbox" class="me-2 checklist-item-checkbox" data-item-id="{{ $item->id }}" {{ $item->is_checked ? 'checked' : '' }}>
                                        <!-- Text with line-through if the item is checked -->
                                        <span class="checklist-item-text" style="{{ $item->is_checked ? 'text-decoration: line-through;' : '' }}">
                                            {{ $item->content }}
                                        </span>
                                    </div>
                                    <!-- Delete button -->
                                    <button class="btn btn-sm btn-light delete-checklist-item-btn" data-item-id="{{ $item->id }}" aria-label="Delete">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
</div>

<!-- Bootstrap Modal for Eisenhower Matrix -->

<div class="modal fade" id="eisenhowerMatrixModal" tabindex="-1" aria-labelledby="eisenhowerMatrixLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg"> <!-- Use modal-lg to make it smaller -->

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="eisenhowerMatrixLabel">Eisenhower Matrix</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;"> <!-- Limit height and add scroll -->

                <x-eisenhower-matrix /> <!-- Display the Eisenhower Matrix here -->

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

            </div>

        </div>

    </div>

</div>
<div id="box-1" class="col-12 col-sm-6 col-md-15 d-flex align-items-center sortable-item" id="teams-box">
                <!--<a href="{{ route('teams.index') }}" class="text-decoration-none w-100">-->
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Teams You Belong To</span>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <i class="fas fa-users"></i>
                                {{ $userTeamsCount }} <!-- Display number of teams user belongs to -->
                            </div>
                        </div>
                    </div>
                 <!--</a>-->
            </div>

            <!-- Task Status Box -->
            <div  id="box-2" class="col-12 col-sm-6 col-md-15 d-flex align-items-center" id="status-box">
                    <div class="info-box mb-3" style="border-radius: 10px;">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-clipboard"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">All Tasks</span>
                            <div class="d-flex align-items-center">
                                <span id="task-status-value" class="h5 mb-0 font-weight-bold">{{ $taskStatusCounts['all'] }}</span>
                                <span class="text-xs text-gray-800 ml-2" id="task-status-label">All</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Not Started Box -->
                <div  id="box-3"  class="col-12 col-sm-6 col-md-15 d-flex align-items-center" id="not-started-box">
                    <div class="info-box mb-3" style="border-radius: 10px;">
                        <span class="info-box-icon bg-secondary elevation-1"><i class="fas fa-hourglass-start"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Not Started</span>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span id="not-started-status-value">{{ $taskStatusCounts['not started'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- In Progress Box -->
                <div id="box-4"  class="col-12 col-sm-6 col-md-15 d-flex align-items-center" id="in-progress-box">
                    <div class="info-box mb-3" style="border-radius: 10px;">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-spinner"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">In Progress</span>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span id="in-progress-status-value">{{ $taskStatusCounts['in progress'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Priority Box -->
            <div  id="box-5"  class="col-12 col-sm-6 col-md-16 d-flex align-items-center" id="low-priority-box">
                    <div class="info-box mb-3" style="border-radius: 10px;">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Low Priority</span>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span id="low-priority-value">{{ $taskPriorities['low'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medium Priority Box -->
                <div  id="box-6" class="col-12 col-sm-6 col-md-16 d-flex align-items-center" id="medium-priority-box">
                    <div class="info-box mb-3" style="border-radius: 10px;">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Medium Priority</span>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span id="medium-priority-value">{{ $taskPriorities['medium'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- High Priority Box -->
                <div  id="box-7"  class="col-12 col-sm-6 col-md-16 d-flex align-items-center" id="high-priority-box">
                    <div class="info-box mb-3" style="border-radius: 10px;">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-exclamation-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">High Priority</span>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <span id="high-priority-value">{{ $taskPriorities['high'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Individual Task Container -->
            <div  id="box-8" class="col-lg-6 mb-4 sortable-item" id="individual-task-box">
                <div class="card shadow h-100 border-0">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title d-flex align-items-center mb-3">
                            <i class="fas fa-tasks mr-2"></i> Individual Tasks
                        </h5>

                        <div class="task-list flex-grow-1">
                            @if($recentIndividualTasks->isEmpty())
                                <div class="task-item">
                                    <p class="text-muted">No recent individual tasks</p>
                                </div>
                            @else
                                @foreach($recentIndividualTasks as $task)
                                <!-- Modernized clickable individual task item -->
                                <a href="{{ route('user.tasks.show', $task->id) }}" class="text-decoration-none">
                                    <div class="task-item d-flex justify-content-between align-items-center py-3">
                                        <div>
                                            <strong>{{ $task->name }}</strong>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted mr-2">Due:</small>
                                            <span class="badge badge-pill badge-warning">{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</span>
                                            <i class="fas fa-chevron-right ml-3 task-icon"></i>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            @endif
                        </div>

                        <!-- Button Section -->
                        <div class="mt-auto d-flex justify-content-between">
                            <a href="{{ route('user.tasks.create') }}" class="btn btn-primary">Add new task</a>
                            <a href="{{ route('user.tasks.index') }}" class="btn btn-info">View tasks</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Task Container -->
            <div  id="box-9" class="col-lg-6 mb-4 sortable-item" id="team-task-box">
                <div class="card shadow h-100 border-0">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title d-flex align-items-center mb-3">
                            <i class="fas fa-users mr-2"></i> Team Tasks
                        </h5>

                        <div class="task-list flex-grow-1">
                            @php $hasAssignedTasks = false; @endphp

                            @foreach($userTeams as $team)
                                @php
                                    $teamTasks = $recentTeamTasks->filter(function($task) use ($team, $user) {
                                        return $task->team_id == $team->id && $task->user_id == $user->id;
                                    });
                                @endphp

                                @if($teamTasks->isNotEmpty())
                                    @php $hasAssignedTasks = true; @endphp
                                    @foreach($teamTasks as $task)
                                    <!-- Modernized clickable team task item -->
                                    <a href="{{ route('user.tasks.show', $task->id) }}" class="text-decoration-none">
                                        <div class="task-item d-flex justify-content-between align-items-center py-3">
                                            {{ $task->team->name }}: {{ $task->name }}
                                            <div class="d-flex align-items-center">
                                                <small class="text-muted mr-2">Due:</small>
                                                <span class="badge badge-pill badge-warning">{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</span>
                                                <i class="fas fa-chevron-right ml-3 task-icon"></i>
                                            </div>
                                        </div>
                                    </a>
                                    @endforeach
                                @endif
                            @endforeach

                            @if(!$hasAssignedTasks)
                                <div class="task-item">
                                    <p class="text-muted">No assigned tasks for now</p>
                                </div>
                            @endif
                        </div>

                        <!-- Button Section -->
                        <div class="mt-auto d-flex justify-content-between">
                            <a href="{{ route('user.teams.create') }}" class="btn btn-primary">Create new team</a>
                            <a href="{{ route('user.teams.index') }}" class="btn btn-info">View teams</a>
                        </div>
                    </div>
                </div>
            </div>

<!-- Task Progress Chart -->
<div id="box-12" class="col-lg-6 mb-4 sortable-item" id="task-chart-box">
    <div class="card shadow h-100 border-0">
        <div class="card-body d-flex flex-column">
            <h5 class="card-title d-flex align-items-center mb-3">
                <i class="fas fa-chart-pie mr-2"></i> Task Progress Chart
            </h5>
            <!-- Dropdown for chart type selection -->
            <div class="text-xs font-weight-bold text-uppercase mb-1">
                Select Chart Type:
                <select id="chartType" class="form-control">
                    <option value="doughnut">Doughnut Chart</option>
                    <option value="bar">Bar Chart</option>
                    <option value="line">Line Chart</option>
                </select>
            </div>
            <div class="chart-container pt-4 pb-2" style="height: 300px;">
                <canvas id="taskChart" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>


<!-- Priority Count Chart -->
<div id="box-13" class="col-lg-6 mb-4 sortable-item" id="priority-chart-box">
    <div class="card shadow h-100 border-0">
        <div class="card-body d-flex flex-column">
            <h5 class="card-title d-flex align-items-center mb-3">
                <i class="fas fa-list-alt mr-2"></i> Priority Count
            </h5>
            <!-- Dropdown for chart type selection -->
            <div class="text-xs font-weight-bold text-uppercase mb-1">
                Select Chart Type:
                <select id="priorityChartType" class="form-control">
                    <option value="doughnut">Doughnut Chart</option>
                    <option value="bar">Bar Chart</option>
                    <option value="line">Line Chart</option>
                </select>
            </div>
            <div class="chart-container pt-4 pb-2" style="height: 300px;">
                <canvas id="priorityChart" style="width: 100%; height: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>


            <!-- Deadlines This Week -->
            <div id="box-14" class="col-lg-12 mb-4 sortable-item" id="deadlines-box">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <h5 class="font-weight-bold text-primary mb-4">Deadlines This Week</h5>
                        <div class="table-responsive">
                            <!-- Add an ID to the table for DataTables targeting -->
                            <table class="table table-bordered" id="deadlinesTable">
                                <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th>Progress</th>
                                        <th>Deadline</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tasks as $task)
                                    <tr>
                                        <td>{{ $task->name }}</td>
                                        <td>
                                            <span class="badge
                                                @if($task->status == 'not started') badge-warning
                                                @elseif($task->status == 'in progress') badge-primary
                                                @elseif($task->status == 'done') badge-success
                                                @endif">
                                                {{ ucfirst($task->status) }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div> <!-- End of single sortable-dashboard -->
    </div>
</x-app-layout>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />

<!-- FullCalendar JavaScript -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

<script>
    $(document).ready(function() {
        // CSRF token for AJAX requests
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // ========================== NOTE WIDGET FUNCTIONS ==========================

        // Add a new note widget dynamically
        $('#add-note-widget').on('click', function() {
            $.ajax({
                url: '/widgets',  // Your route for creating widgets
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    type: 'note',    // Define widget type
                    content: ''      // Initial content is empty
                },
                success: function(widget) {
                    // Create the new widget element with a minimize and close button
                    const newWidget = `
                        <div class="col-12 col-sm-6 col-md-3 sortable-item mb-3" id="widget-${widget.id}">
                            <div class="card shadow h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Note</h5>
                                    <div class="widget-actions ms-auto">
                                        <!-- Minimize button -->
                                        <button type="button" class="btn btn-sm btn-secondary minimize-note-widget" data-widget-id="${widget.id}" aria-label="Minimize">
                                            <span aria-hidden="true">−</span>
                                        </button>
                                        <!-- Close button -->
                                        <button type="button" class="close remove-note-widget" aria-label="Close" data-widget-id="${widget.id}">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body note-content" id="note-content-${widget.id}">
                                    <textarea class="form-control mb-2 note-input"></textarea>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-success save-note-btn" data-widget-id="${widget.id}">Add</button>
                                    </div>
                                    <!-- Properly styled bulleted list -->
                                    <ul class="note-list mt-3 list-unstyled" id="note-list-${widget.id}"></ul>
                                </div>
                            </div>
                        </div>`;

                    // Append the new widget to the existing notes container
                    $('#notes-container').append(newWidget);
                },
                error: function(xhr, status, error) {
                    console.error('Error creating note widget:', error);
                }
            });
        });

        // Save the note widget's content and add it as a bullet point with an X button
        $(document).on('click', '.save-note-btn', function() {
            const widgetId = $(this).data('widget-id');
            const content = $(this).closest('.card-body').find('.note-input').val();

            if (content.trim() === '') {
                alert('Note content cannot be empty.');
                return;
            }

            $.ajax({
                url: `/widgets/${widgetId}/notes`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    content: content
                },
                success: function(response) {
                    if (response.note && response.note.id && response.note.content) {
                        // Add the note as a bullet point with an X button
                        $(`#note-list-${widgetId}`).append(`
                            <li class="d-flex justify-content-between align-items-center mb-2">
                                <span>${response.note.content}</span>
                                <button class="btn btn-sm btn-light delete-note-item-btn" data-note-id="${response.note.id}" aria-label="Delete">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </li>
                        `);
                    } else {
                        console.error('Invalid response from server:', response);
                    }
                    $(this).closest('.card-body').find('.note-input').val('');
                }.bind(this),
                error: function(xhr, status, error) {
                    console.error('Error saving note:', error);
                }
            });
        });

        // Remove a note item
        $(document).on('click', '.delete-note-item-btn', function() {
            const noteId = $(this).data('note-id');

            if (!noteId) {
                console.error('Error: noteId is undefined');
                return;
            }

            $.ajax({
                url: `/notes/${noteId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Remove the note item from the DOM
                    $(this).closest('li').remove();
                }.bind(this),
                error: function(xhr, status, error) {
                    console.error('Error removing note item:', error);
                }
            });
        });

        // Remove a note widget
        $(document).on('click', '.remove-note-widget', function() {
            const widgetId = $(this).data('widget-id');

            $.ajax({
                url: `/widgets/${widgetId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Remove the widget from the DOM
                    $(`#widget-${widgetId}`).remove();
                    console.log('Note widget removed successfully');
                },
                error: function(xhr, status, error) {
                    console.error('Error removing note widget:', error);
                }
            });
        });

        // Minimize/Maximize the note widget
        $(document).on('click', '.minimize-note-widget', function() {
            const widgetId = $(this).data('widget-id');
            const contentDiv = $(`#note-content-${widgetId}`);
            contentDiv.slideToggle();
        });
          // ========================== CHECKLIST WIDGET FUNCTIONS ==========================

    // Add a new checklist widget dynamically
    $('#add-checklist-widget').on('click', function() {
        $.ajax({
            url: '/widgets',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                type: 'checklist' // Define widget type as checklist
            },
            success: function(widget) {
                // Create the new widget element with minimize and close buttons
                const newWidget = `
                    <div class="col-12 col-sm-6 col-md-3 sortable-item mb-3" id="widget-${widget.id}">
                        <div class="card shadow h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Checklist</h5>
                                <div class="widget-actions ms-auto">
                                    <!-- Minimize button -->
                                    <button type="button" class="btn btn-sm btn-secondary minimize-checklist-widget" data-widget-id="${widget.id}" aria-label="Minimize">
                                        <span aria-hidden="true">−</span>
                                    </button>
                                    <!-- Close button -->
                                    <button type="button" class="close remove-checklist-widget" aria-label="Close" data-widget-id="${widget.id}">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body checklist-content" id="checklist-content-${widget.id}">
                                <input type="text" class="form-control mb-2 checklist-input" placeholder="Add checklist item">
                                <button class="btn btn-primary add-checklist-item-btn" data-widget-id="${widget.id}">Add Item</button>
                                <ul class="checklist-list mt-3" id="checklist-list-${widget.id}"></ul>
                            </div>
                        </div>
                    </div>`;

                // Append the new widget to the existing checklist container
                $('#checklist-container').append(newWidget);
            },
            error: function(xhr, status, error) {
                console.error('Error creating checklist widget:', error);
            }
        });
    });

    // Remove the entire checklist widget
    $(document).on('click', '.remove-checklist-widget', function() {
        const widgetId = $(this).data('widget-id');

        // Remove the widget from the DOM
        $(`#widget-${widgetId}`).remove();

        // Send an AJAX request to delete the widget from the database
        $.ajax({
            url: `/widgets/${widgetId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log('Checklist widget removed successfully');
            },
            error: function(xhr, status, error) {
                console.error('Error removing checklist widget:', error);
            }
        });
    });

    // Add a new checklist item within a widget
    $(document).on('click', '.add-checklist-item-btn', function() {
        const widgetId = $(this).data('widget-id');
        const input = $(this).siblings('.checklist-input');
        const content = input.val();

        if (content.trim() === '') {
            alert('Checklist item content cannot be empty.');
            return;
        }

        $.ajax({
            url: '/checklist-item/store',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: {
                widget_id: widgetId,
                content: content
            },
            success: function(response) {
                if (response.id && response.content) {
                    // Add the new item with side-by-side layout and X button
                    $(`#checklist-list-${widgetId}`).append(`
                        <li class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <input type="checkbox" class="me-2 checklist-item-checkbox" data-item-id="${response.id}">
                                <span class="checklist-item-text">${response.content}</span>
                            </div>
                            <button class="btn btn-sm btn-light delete-checklist-item-btn" data-item-id="${response.id}" aria-label="Delete">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </li>
                    `);
                } else {
                    console.error('Invalid response from server:', response);
                }
                input.val('');
            },
            error: function(xhr, status, error) {
                console.error('Error adding checklist item:', error);
            }
        });
    });

    // Delete a checklist item
    $(document).on('click', '.delete-checklist-item-btn', function() {
        const itemId = $(this).data('item-id');

        if (!itemId) {
            console.error('Error: itemId is undefined');
            return;
        }

        $.ajax({
            url: `/checklist-item/delete/${itemId}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                $(this).closest('li').remove();
            }.bind(this),
            error: function(xhr, status, error) {
                console.error('Error deleting checklist item:', error);
            }
        });
    });

    // Update checklist item status (checked/unchecked) and add line-through if checked
    $(document).on('change', '.checklist-item-checkbox', function() {
        const itemId = $(this).data('item-id');
        const isChecked = $(this).is(':checked');
        const itemText = $(this).siblings('.checklist-item-text');

        // Toggle line-through style based on checkbox state
        if (isChecked) {
            itemText.css('text-decoration', 'line-through');
        } else {
            itemText.css('text-decoration', 'none');
        }

        if (!itemId) {
            console.error('Error: itemId is undefined');
            return;
        }

        $.ajax({
            url: `/checklist-item/update/${itemId}`,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            contentType: 'application/json',
            data: JSON.stringify({ is_checked: isChecked }),
            success: function(response) {
                console.log('Checklist item updated successfully');
            },
            error: function(xhr, status, error) {
                console.error('Error updating checklist item:', error);
            }
        });
    });

    // Minimize/Maximize the checklist widget
    $(document).on('click', '.minimize-checklist-widget', function() {
        const widgetId = $(this).data('widget-id');
        const contentDiv = $(`#checklist-content-${widgetId}`);
        contentDiv.slideToggle();
    });

    });
</script>


<script>
    $(document).ready(function() {
        // Initialize DataTables for the deadlines table
        $('#deadlinesTable').DataTable({
            "paging": true,       // Enable pagination
            "pageLength": 10,     // Show 10 entries per page
            "lengthChange": false, // Disable changing the page length
            "searching": true,    // Enable search filter
            "ordering": true,     // Enable column sorting
            "info": true,         // Show info about the number of entries
            "autoWidth": false,   // Disable automatic column width calculation
            "language": {
                "paginate": {
                    "previous": "<",
                    "next": ">"
                }
            }
        });
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get the login time from the server (Laravel Blade passes it as JSON)
        var loginTime = new Date(@json($latestSession->login_time));

        // Function to update the session timer every second
        function updateSessionTimer() {
            var now = new Date();
            var diff = now - loginTime;  // Difference in milliseconds

            // Calculate hours, minutes, and seconds from the difference
            var hours = Math.floor(diff / 3600000);
            var minutes = Math.floor((diff % 3600000) / 60000);
            var seconds = Math.floor((diff % 60000) / 1000);

            // Display the timer in the "session-timer" span
            document.getElementById('session-timer').innerText =
                (hours < 10 ? '0' + hours : hours) + 'h ' +
                (minutes < 10 ? '0' + minutes : minutes) + 'm ' +
                (seconds < 10 ? '0' + seconds : seconds) + 's';
        }

        // Update the timer every second
        setInterval(updateSessionTimer, 1000);
    });
</script>

<style>
    
    .card {
    min-width: 250px; /* Ensures a minimum width for each widget */
    flex: 1; /* Makes widgets flexible in width */
    margin-bottom: 15px; /* Adjust the bottom margin */
}

    .flex-container {
    display: flex;
    flex-wrap: wrap; /* Allows wrapping if there's not enough space */
    gap: 15px; /* Adjust the gap between widgets */
}

.note-list {
    list-style-type: disc; /* Ensure default bullets are shown */
    padding-left: 20px;    /* Adds padding to align bullets */
}

.list-item {
    margin-bottom: 5px;    /* Spacing between notes */
}
.checklist {
    margin-top: 10px;
}

.checklist-items {
    list-style: none;
    padding: 0;
    max-height: 200px;
    overflow-y: auto;
}

.checklist-item {
    display: flex;
    align-items: center;
    padding: 5px;
    background-color: #f8f9fa;
    border-radius: 5px;
    margin-bottom: 5px;
    box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
}

.checklist-item input[type="checkbox"] {
    margin-right: 10px;
}

.checklist-item button {
    margin-left: auto;
}

.checklist-item:hover {
    background-color: #e2e6ea;
}

.checklist .input-group {
    margin-top: 10px;
}

.completed {
    text-decoration: line-through;
    color: #888; /* Gray out the text to indicate completion */
}


.eisenhower-matrix {
    margin-top: 10px;
}
.eisenhower-matrix .quadrant {
    min-height: 150px;
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
}
.eisenhower-matrix h6 {
    font-weight: bold;
    margin-bottom: 5px;
}
.eisenhower-matrix ul {
    list-style: none;
    padding: 0;
}
.eisenhower-matrix li {
    padding: 5px;
    margin-bottom: 5px;
    background-color: #ffffff;
    border-radius: 5px;
    box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
    cursor: pointer;
}
.eisenhower-matrix li:hover {
    background-color: #e2e6ea;
}


.mini-calendar {
    margin-top: 10px;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.mini-calendar .fc-toolbar-title {
    font-size: 16px;
    font-weight: bold;
}

.mini-calendar .fc-prev-button, .mini-calendar .fc-next-button {
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 50%;
    padding: 5px;
}

.mini-calendar .fc-prev-button:hover, .mini-calendar .fc-next-button:hover {
    background-color: #0056b3;
}

.mini-calendar .fc-daygrid-day {
    cursor: pointer;
}

.mini-calendar .fc-daygrid-day:hover {
    background-color: #e9ecef;
}


    .widget-actions {
    display: flex;
    gap: 5px;
}

.widget-actions .btn {
    width: 30px;
    height: 30px;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    font-size: 14px;
}

    .session-timer {
        padding: 15px;
        border: 1px solid #eee;
        background-color: #f9f9f9;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .session-timer h4 {
        margin-bottom: 10px;
    }
    .session-timer p {
        font-size: 16px;
        color: #333;
    }
</style>

    <!-- Custom CSS -->
    <style>
    /* General styles for the task list */
    .task-item {
        padding: 10px 0;
        border-bottom: 1px solid #eee; /* Optional subtle divider */
        transition: background-color 0.3s ease, box-shadow 0.3s ease; /* Smooth transitions */
    }
    .task-item:hover {
        background-color: #f8f9fa;
        cursor: pointer;
        box-shadow: 0px 3px 6px rgba(0,0,0,0.1); /* Adds subtle shadow effect */
    }
    .task-item:last-child {
        border-bottom: none; /* No border for the last item */
    }

    /* Chevron icon transition */
    .task-icon {
        color: #6c757d;
        opacity: 0;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    /* Show the chevron icon and move it slightly on hover */
    .task-item:hover .task-icon {
        opacity: 1;
        transform: translateX(5px);
    }

    /* Remove card border for a cleaner, more modern layout */
    .card {
        border: none;
    }

    /* Scrollable task list */
    .task-list {
        max-height: 200px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .task-list::-webkit-scrollbar {
        width: 4px;
    }
    .task-list::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }
</style>

<!-- JavaScript for Sortable Dashboard and Layout Saving -->
<script>
$(function () {
    // Enable sorting for all dashboard boxes
    $(".sortable-dashboard").sortable({
        placeholder: "ui-state-highlight", // Optional styling for the dragged placeholder
        update: function (event, ui) {
            let sortedIDs = $(this).sortable("toArray"); // Get the new order of elements
            console.log(sortedIDs); // Log the new order for testing

            // Save the order to the server using AJAX
            $.ajax({
                url: "{{ route('user.saveDashboardLayout') }}", // Adjust this to the correct route
                method: "POST",
                data: {
                    order: sortedIDs,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log('Dashboard order saved successfully.');
                },
                error: function() {
                    console.log('Error saving the dashboard order.');
                }
            });
        }
    });

    // Disable text selection while dragging
    $(".sortable-dashboard").disableSelection();

    // Apply saved dashboard layout (if any) on page load
    let savedOrder = @json($dashboardOrder); // Get the saved layout from the controller

    if (savedOrder && savedOrder.length > 0) {
        // Loop through the saved IDs and append the elements in the correct order
        $.each(savedOrder, function (index, value) {
            $("#" + value).appendTo(".sortable-dashboard");
        });
    }
});
</script>


<script>
 document.addEventListener('DOMContentLoaded', function () {
    const taskData = @json($taskData); // Assuming this is server-side injected JSON object

    // Chart colors
    const colors = {
        'not started': '#54a0ff',
        'in progress': '#f6b93b',
        'done': '#78e08f'
    };

    // Chart Data
    const chartData = {
        labels: Object.keys(taskData),
        datasets: [{
            data: Object.values(taskData),
            backgroundColor: Object.keys(taskData).map(status => colors[status] || '#ccc'),
            borderColor: "#ffffff",
            borderWidth: 5,
        }]
    };

    // Initial Chart Setup (Donut by default)
    let chartType = 'doughnut'; // Default chart type
    const ctx = document.getElementById('taskChart').getContext('2d');
    let taskChart = new Chart(ctx, {
        type: chartType,
        data: chartData,
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                displayColors: false,
            },
            legend: { display: false },
        },
    });

    // Event listener for chart type selection
    document.getElementById('chartType').addEventListener('change', function (e) {
        const selectedChartType = e.target.value;

        // Destroy the existing chart and create a new one with the selected type
        taskChart.destroy();
        taskChart = new Chart(ctx, {
            type: selectedChartType,
            data: {
                labels: Object.keys(taskData),
                datasets: [{
                    label: 'Tasks',
                    data: Object.values(taskData),
                    backgroundColor: selectedChartType === 'line'
                        ? "rgba(28, 200, 138, 0.2)"
                        : Object.keys(taskData).map(status => colors[status] || '#ccc'),
                    borderColor: selectedChartType === 'line' ? "#1cc88a" : "#ffffff",
                    borderWidth: selectedChartType === 'line' ? 2 : 1,
                    fill: selectedChartType === 'line', // Only fill for line chart
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    displayColors: false,
                },
                legend: { display: false },
                scales: selectedChartType === 'bar' || selectedChartType === 'line' ? {
                    xAxes: [{
                        gridLines: { display: false },
                        ticks: { beginAtZero: true }
                    }],
                    yAxes: [{
                        gridLines: { display: true },
                        ticks: { beginAtZero: true }
                    }]
                } : {}
            },
        });
    });
});


</script>
<script>


document.addEventListener("DOMContentLoaded", function () {
    const taskPriorities = <?php echo json_encode($taskPriorities, 15, 512) ?>;

    // Explicitly assign values to ensure the correct order
    const priorityCounts = [
        taskPriorities['low'] || 0,     // Low priority count
        taskPriorities['medium'] || 0,  // Medium priority count
        taskPriorities['high'] || 0     // High priority count
    ];

    // Debugging step to ensure data is correct
    console.log('Task Priorities:', taskPriorities);
    console.log('Priority Counts (Low, Medium, High):', priorityCounts);

    // Check if the canvas element exists
    const priorityCtx = document.getElementById('priorityChart');
    if (!priorityCtx) {
        console.error('Canvas element for priority chart is missing.');
        return;
    }

    // Initial Chart Setup (Donut by default)
    let priorityChartType = 'doughnut'; // Default chart type
    const priorityCtx2d = priorityCtx.getContext('2d');
    let priorityChart = new Chart(priorityCtx2d, {
        type: priorityChartType,
        data: {
            labels: ['Low', 'Medium', 'High'],  // Priority labels
            datasets: [{
                data: priorityCounts,
                backgroundColor: ['#1dd1a1', '#feca57', '#ff6b6b'],
                borderColor: "#ffffff",
                borderWidth: 5,
            }]
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                displayColors: false,
            },
            legend: { display: false },  // Hide the legend similar to the left chart
            scales: priorityChartType === 'bar' || priorityChartType === 'line' ? {
                xAxes: [{
                    gridLines: { display: false },
                    ticks: { beginAtZero: true }
                }],
                yAxes: [{
                    gridLines: { display: true },
                    ticks: { beginAtZero: true }
                }]
            } : {}
        }
    });

    // Event listener for chart type selection
    document.getElementById('priorityChartType').addEventListener('change', function (e) {
        const selectedChartType = e.target.value;

        // Destroy the existing chart and create a new one with the selected type
        priorityChart.destroy();
        priorityChart = new Chart(priorityCtx2d, {
            type: selectedChartType,
            data: {
                labels: ['Low', 'Medium', 'High'],
                datasets: [{
                    data: priorityCounts,
                    backgroundColor: selectedChartType === 'line'
                        ? "rgba(0, 123, 255, 0.2)"
                        : ['#ff6b6b', '#feca57', '#1dd1a1'],
                    borderColor: selectedChartType === 'line' ? "#007bff" : "#ffffff",
                    borderWidth: selectedChartType === 'line' ? 2 : 1,
                    fill: selectedChartType === 'line', // Only fill for line chart
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    displayColors: false,
                },
                legend: { display: false },  // Ensure legend is hidden across all chart types
                scales: selectedChartType === 'bar' || selectedChartType === 'line' ? {
                    xAxes: [{
                        gridLines: { display: false },
                        ticks: { beginAtZero: true }
                    }],
                    yAxes: [{
                        gridLines: { display: true },
                        ticks: { beginAtZero: true }
                    }]
                } : {}
            }
        });
    });




    // Priority changing mechanism (if you want to update the chart dynamically)
    let currentPriorityIndex = 0;

    function updatePriorityDisplay() {
        const priority = priorities[currentPriorityIndex];
        const priorityCount = taskPriorities[priority];
        const priorityLabel = priority.charAt(0).toUpperCase() + priority.slice(1);

        document.getElementById('priority-value').innerText = priorityCount;
        document.getElementById('priority-label').innerText = priorityLabel;

        const iconElement = document.getElementById('priority-icon');
        iconElement.classList.remove('text-danger', 'text-warning', 'text-success');

        if (priority === 'high') {
            iconElement.className = 'fas fa-exclamation-circle text-danger';
        } else if (priority === 'medium') {
            iconElement.className = 'fas fa-exclamation-triangle text-warning';
        } else if (priority === 'low') {
            iconElement.className = 'fas fa-check-circle text-success';
        }

        // Update the chart with new data if needed
        priorityChart.data.datasets[0].data[currentPriorityIndex] = priorityCount;
        priorityChart.update();
    }

    window.changePriority = function(direction) {
        if (direction === 'next') {
            currentPriorityIndex = (currentPriorityIndex + 1) % priorities.length;
        } else {
            currentPriorityIndex = (currentPriorityIndex - 1 + priorities.length) % priorities.length;
        }
        updatePriorityDisplay();
    };

    updatePriorityDisplay();
});

</script>
<script>
    document.getElementById('reset-layout-btn').addEventListener('click', function() {
        if (confirm('Are you sure you want to reset the dashboard layout to its default?')) {
            $.ajax({
                url: "{{ route('user.resetDashboardLayout') }}",  // Ensure the route is correct
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",  // Include CSRF token
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();  // Reload the page after reset
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);  // Log any error for debugging
                    alert('An error occurred while resetting the layout.');
                }
            });
        }
    });
</script>


