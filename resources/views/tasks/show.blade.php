<x-app-layout>
    <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Details') }}
            </h2>
            <!-- Back Button aligned with Task Details text -->
            <a href="javascript:void(0);" id="task-details-back-btn" onclick="history.back();" class="btn btn-danger btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow mb-4">
                        <!-- Header Section with Task Title and Edit Button (aligned to the far right) -->
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 id="task-header-title" class="m-0 font-weight-bold text-primary">Task Details</h6>
                            <div class="ml-auto"> <!-- Added ml-auto to push button to far right -->
                                <!-- Edit Button -->
                                @if(Auth::user()->usertype == 'admin' || Auth::id() == $task->user_id || ($task->team && Auth::id() == $task->team->creator_id))
                                    <button id="edit-task-btn" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="card-body">
                            <div id="task-details">
                                <p><strong>Name:</strong> {{ $task->name }}</p>
                                <p><strong>Priority:</strong> {{ ucfirst($task->priority) }}</p>
                                  <!-- Status Update Section -->
                                  <form id="task-status-form" method="POST" action="{{ route('user.tasks.updateStatus', $task->id) }}">
    @csrf
    @method('PATCH')

    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control" required>
            <option value="not started" {{ $task->status == 'not started' ? 'selected' : '' }}>Not Started</option>
            <option value="in progress" {{ $task->status == 'in progress' ? 'selected' : '' }}>In Progress</option>
            <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Update Status</button>
</form>


                                <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</p>
                                <p><strong>Date Started:</strong> {{ $task->date_started ? \Carbon\Carbon::parse($task->date_started)->format('Y-m-d') : 'N/A' }}</p>
                                <p><strong>Description:</strong> {{ $task->description }}</p>
                                @if ($task->status == 'done')
                                    <p><strong>Duration:</strong> {{ $task->duration ?? 'N/A' }}</p>
                                @endif


                                <!-- Checklist Section -->
                                <h6 class="font-weight-bold">Checklist:</h6>
                                @if(Auth::id() == $task->user_id || Auth::user()->usertype == 'admin' || ($task->team && Auth::id() == $task->team->creator_id))
                                    <form method="POST" action="{{ route('user.tasks.addChecklistItem', $task->id) }}">
                                        @csrf
                                        <div class="input-group mb-3">
                                            <input type="text" name="item" class="form-control" placeholder="Add a checklist item" required>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">Add</button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                                <ul class="list-group mb-3">
                                    @foreach ($task->checklists as $checklist)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <input type="checkbox" class="form-check-input me-2" 
                                                    onchange="updateChecklistItem({{ $task->id }}, {{ $checklist->id }}, this)" 
                                                    {{ $checklist->is_completed ? 'checked' : '' }} 
                                                    @if(!(Auth::id() == $task->user_id || Auth::user()->usertype == 'admin' || ($task->team && Auth::id() == $task->team->creator_id)))
                                                        disabled
                                                    @endif
                                                >
                                                {{ $checklist->item }}
                                            </div>
                                            @if(Auth::id() == $task->user_id || Auth::user()->usertype == 'admin' || ($task->team && Auth::id() == $task->team->creator_id))
                                                <form method="POST" action="{{ route('user.tasks.deleteChecklistItem', [$task->id, $checklist->id]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="mb-4">
                                    <strong>Progress: </strong>
                                    <div class="progress">
                                        @php
                                            $total = $task->checklists->count();
                                            $completed = $task->checklists->where('is_completed', true)->count();
                                            $percentage = $total > 0 ? ($completed / $total) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">{{ round($percentage, 2) }}%</div>
                                    </div>
                                </div>

                                <!-- Attachments Section -->
                                <p><strong>Attachments:</strong></p>
                                <ul class="list-group">
                                    @foreach ($task->attachments as $attachment)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ basename($attachment->file_path) }}
                                            <div>
                                                <a href="{{ route('user.tasks.downloadAttachment', [$task->id, $attachment->id]) }}" class="btn btn-sm btn-success">
                                                    <i class="fas fa-download"></i> Download
                                                </a>
                                                <!-- Preview Button -->
                                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-solid fa-binoculars"></i> </i>Preview
                                                </a>
                                                @if(Auth::id() == $task->user_id || Auth::user()->usertype == 'admin' || ($task->team && Auth::id() == $task->team->creator_id))
                                                    <form method="POST" action="{{ route('user.tasks.removeAttachment', [$task->id, $attachment->id]) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i> Remove
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>

                            <div id="edit-task-form" style="display: none;">
                            


                            <form id="edit-task-form" method="POST" action="{{ route('user.tasks.update', $task->id) }}" enctype="multipart/form-data">
                            
                                    @csrf
                                    @method('PUT')

                                    @php
                                        $isAdminOrCreator = Auth::user()->usertype == 'admin' || ($task->team && Auth::id() == $task->team->creator_id);
                                        $isOwnTask = !$task->team && Auth::id() == $task->user_id;
                                    @endphp

                                    <div class="form-group">
                                        <label for="name">Task Name</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ $task->name }}" {{ !$isAdminOrCreator && !$isOwnTask ? 'readonly' : '' }}>
                                    </div>
                                    <div class="form-group">
                                        <label for="priority">Priority</label>
                                        <select name="priority" id="priority" class="form-control" {{ !$isAdminOrCreator && !$isOwnTask ? 'disabled' : '' }}>
                                            <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="due_date">Due Date</label>
                                        <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}" {{ !$isAdminOrCreator && !$isOwnTask ? 'readonly' : '' }}>
                                    </div>
                                    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control" required>
            <option value="not started" {{ $task->status == 'not started' ? 'selected' : '' }}>Not Started</option>
            <option value="in progress" {{ $task->status == 'in progress' ? 'selected' : '' }}>In Progress</option>
            <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done</option>
        </select>
    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control">{{ $task->description }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="attachments">Attachments</label>
                                        <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                                    </div>
                                    <div class="form-group">
        <label for="date_started">Date Started</label>
        <input type="text" name="date_started" id="date_started" class="form-control" value="{{ $task->date_started ? \Carbon\Carbon::parse($task->date_started)->format('Y-m-d') : 'N/A' }}" readonly>
    </div>

    @if($isAdminOrCreator)
    <div class="form-group">
        <label for="assignee">Assign To</label>
        <select name="assignee" id="assignee" class="form-control">
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
@endif

<div class="form-group d-flex justify-content-between">
    <button type="submit" class="btn btn-primary">Update Task</button>
    <button type="button" class="btn btn-danger" id="edit-task-back-btn">
        <i class="fas fa-arrow-left"></i> Back
    </button>
</div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Ensure you have the necessary FontAwesome and SB Admin 2 scripts included -->
<link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/sb-admin-2.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editTaskBtn = document.getElementById('edit-task-btn');
        var taskTitle = document.getElementById('task-title');
        var taskHeaderTitle = document.getElementById('task-header-title');
        var taskDetailsBackBtn = document.getElementById('task-details-back-btn'); // Task Details Back Button
        var editTaskBackBtn = document.getElementById('edit-task-back-btn'); // Edit Task Back Button

        // Initially hide the Edit Task back button
        if (editTaskBackBtn) {
            editTaskBackBtn.style.display = 'none';
        }

        // Toggle between task details and edit form
        if (editTaskBtn) {
            editTaskBtn.addEventListener('click', function (event) {
                event.preventDefault();
                document.getElementById('task-details').style.display = 'none';
                document.getElementById('edit-task-form').style.display = 'block';
                editTaskBtn.style.display = 'none'; // Hide Edit button
                taskTitle.textContent = 'Edit Task';
                taskHeaderTitle.textContent = 'Edit Task';

                // Show Edit Task back button and hide Task Details back button
                if (editTaskBackBtn) {
                    editTaskBackBtn.style.display = 'inline-block';
                }
                if (taskDetailsBackBtn) {
                    taskDetailsBackBtn.style.display = 'none';
                }
            });
        }

        // Toggle from Edit Task form back to Task Details
        if (editTaskBackBtn) {
            editTaskBackBtn.addEventListener('click', function () {
                document.getElementById('edit-task-form').style.display = 'none';
                document.getElementById('task-details').style.display = 'block';
                editTaskBtn.style.display = 'inline-block'; // Show Edit button
                taskTitle.textContent = 'Task Details';
                taskHeaderTitle.textContent = 'Task Details';

                // Show Task Details back button and hide Edit Task back button
                if (taskDetailsBackBtn) {
                    taskDetailsBackBtn.style.display = 'inline-block';
                }
                if (editTaskBackBtn) {
                    editTaskBackBtn.style.display = 'none';
                }
            });
        }
    });

    // Handle checklist item updates
    function updateChecklistItem(taskId, checklistId, checkbox) {
        fetch(`/tasks/${taskId}/checklists/${checklistId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                is_completed: checkbox.checked
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log(data.message);

            // Update the progress bar
            var totalItems = {{ $task->checklists->count() }};
            var completedItems = document.querySelectorAll('input[type="checkbox"]:checked').length;
            var percentage = totalItems > 0 ? (completedItems / totalItems) * 100 : 0;
            var progressBar = document.querySelector('.progress-bar');
            progressBar.style.width = percentage + '%';
            progressBar.setAttribute('aria-valuenow', percentage);
            progressBar.textContent = Math.round(percentage) + '%';
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Handle task status update via AJAX
    document.addEventListener('DOMContentLoaded', function () {
        var taskStatusForm = document.getElementById('task-status-form');

        if (taskStatusForm) {
            taskStatusForm.addEventListener('submit', function (event) {
                event.preventDefault();  // Prevent normal form submission

                var status = document.getElementById('status').value;
                var taskId = '{{ $task->id }}';

                // Send the data via AJAX
                fetch('/tasks/' + taskId + '/status', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);

                        // Update the status field in the Task Details form
                        document.getElementById('status').value = data.status.toLowerCase();

                        // Update status in the "Edit Task" form
                        const editFormStatusSelect = document.querySelector('#edit-task-form select[name="status"]');
                        if (editFormStatusSelect) {
                            editFormStatusSelect.value = data.status.toLowerCase();  // Sync the status field in Edit form
                        }

                        // Update date_started and duration if available
                        if (data.date_started) {
                            document.querySelector('p[data-date-started]').textContent = 'Date Started: ' + data.date_started;
                            document.getElementById('date_started').value = data.date_started;  // Update in the Edit Task form
                        }

                        if (data.duration) {
                            document.querySelector('p[data-duration]').textContent = 'Duration: ' + data.duration;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error updating task status:', error);
                });
            });
        }
    });

    
</script>
