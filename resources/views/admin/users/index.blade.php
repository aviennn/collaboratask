<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i> Go Back
                </a>
            </div>

            <!-- Card Container -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold text-primary">User Management</h3>
                </div>
                <!-- Table Container -->
                <div class="card-body">
    <div class="table-responsive">
        <table id="user-table" class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Total Tasks</th>
                    <th>Tasks Not Started</th>
                    <th>Tasks In Progress</th>
                    <th>Tasks Done</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->tasks_count }}</td>
                        <td>{{ $user->tasks_not_started_count }}</td>
                        <td>{{ $user->tasks_in_progress_count }}</td>
                        <td>{{ $user->tasks_done_count }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Actions">
                                <!-- View Button -->
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm mx-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <!-- Edit Button -->
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm mx-1">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <!-- Delete Button -->
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm mx-1" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Pagination Links -->
    </div>
</div>

                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
</x-app-layout>
<script>
    $(document).ready(function() {
        $('#user-table').DataTable({
            "paging": true,       // Enable pagination
            "pageLength": 5,     // Show 10 entries per page
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

<!-- Custom Styles -->
<style>
    .btn-group .btn {
        margin-right: 5px; /* Ensure proper spacing between buttons */
    }

    /* Aligning icons within the table buttons */
    .btn .fas {
        vertical-align: middle;
    }

    /* Styling for a clean table */
    table {
        font-size: 0.9rem;
    }

    .table th, .table td {
        vertical-align: middle;
        text-align: center; /* Centering text in the table */
    }

    .table-hover tbody tr:hover {
        background-color: #f9f9f9;
    }

    /* Back button styling */
    .btn-secondary i {
        margin-right: 5px;
    }

    .card {
        border-radius: 0.5rem;
        border: none;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e3e6f0;
    }
</style>
