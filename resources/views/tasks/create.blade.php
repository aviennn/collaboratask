<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Create a New Task</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.tasks.store') }}" enctype="multipart/form-data">
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
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" required disabled>
                                    <option value="not started" selected>Not Started</option>
                                    <option value="in progress">In Progress</option>
                                    <option value="done">Done</option>
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

                            <!-- Button Section with Go Back Button -->
                            <div class="form-group mt-3 d-flex justify-content-between">
                                <!-- Go Back Button -->
                                <a href="{{ url()->previous() }}" class="btn btn-danger">Go Back</a>
                                
                                <!-- Create Task Button -->
                                <button type="submit" class="btn btn-primary">Create Task</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>


<!-- Ensure you have the necessary jQuery included 
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>-->
<script>
  $(document).ready(function() {
        // Function to add consistent checklist item
        function addChecklistItem() {
            var checklistItem = `
                <div class="input-group mb-2 checklist-group">
                    <input type="text" name="checklists[]" class="form-control checklist-input" placeholder="Checklist item">
                    <div class="input-group-append">
                        <button class="btn btn-danger remove-checklist-item" type="button">Remove</button>
                    </div>
                </div>`;
            $('#checklist-items').append(checklistItem);
        }

        // Add checklist item on button click
        $('#add-checklist-item').click(function() {
            addChecklistItem();
        });

        // Remove checklist item
        $(document).on('click', '.remove-checklist-item', function() {
            $(this).closest('.input-group').remove();
        });
    });
</script>
<style>
    .checklist-group {
        height: 38px;
    }
    .checklist-input, .input-group-append .btn {
        height: 38px;
        padding: 5px 10px;
    }
</style>