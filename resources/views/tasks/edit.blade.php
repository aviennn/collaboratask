<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Edit Task</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('user.tasks.update', $task->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Task Name</label>
                                    <input type="text" name="name" id="name" class="form-control" value="{{ $task->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="priority">Priority</label>
                                    <select name="priority" id="priority" class="form-control" required>
                                        <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
                                    </select>
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
                                    <label for="due_date">Due Date</label>
                                    <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $task->due_date }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" id="description" class="form-control">{{ $task->description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="attachment">Attachment</label>
                                    <input type="file" name="attachment" id="attachment" class="form-control">
                                    @if ($task->attachment)
                                        <a href="{{ route('user.tasks.download', $task->id) }}" class="btn btn-link">Download Current Attachment</a>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="date_started">Date Started</label>
                                    <input type="text" name="date_started" id="date_started" class="form-control" value="{{ $task->date_started ? \Carbon\Carbon::parse($task->date_started)->format('Y-m-d') : 'N/A' }}" readonly>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Update Task</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Ensure you have the necessary SB Admin 2 scripts included -->
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

<!-- Custom script for handling status change -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var statusElement = document.getElementById('status');
        if (statusElement) {
            statusElement.addEventListener('change', function () {
                if (this.value === 'in progress') {
                    document.getElementById('date_started').value = '{{ now()->format('Y-m-d') }}';
                } else if (this.value === 'not started') {
                    document.getElementById('date_started').value = '';
                }
            });
        } else {
            console.error('Status element not found');
        }
    });
</script>
