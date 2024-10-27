<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teams') }}
        </h2>
    </x-slot>

    <!-- Flash Messages Section -->
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- View Switcher Buttons -->
            <div class="d-flex justify-content-end mb-3">
                <button id="table-view-btn" class="btn btn-secondary">Table View</button>
                <button id="card-view-btn" class="btn btn-primary ml-2">Card View</button>
            </div>
<!-- Table Layout -->
<div id="table-view-container" class="card shadow-lg" style="display: block;">
    <div class="card-header">
        <h3 class="card-title">List of Teams</h3>
    </div>
    <div class="card-body">
        <table id="teams-table" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Team Image</th>
                    <th>Name</th>
                    <th>Creator</th>
                    <th>Members</th>
                    <th>Completion</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($teams as $team)
                    <tr>
                        <td>
                            @if ($team->image)
                                <img src="{{ asset('storage/' . $team->image) }}" alt="{{ $team->name }}" class="rounded-circle" style="max-width: 60px;">
                            @endif
                        </td>
                        <td>{{ $team->name }}</td>
                        <td>{{ $team->creator->name }}</td>
                        <td>{{ $team->members->count() }}</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar"
                                     role="progressbar"
                                     style="width: {{ $team->completionPercentage() }}%;
                                            background-color: {{ $team->completionPercentage() >= 70 ? '#28a745' : ($team->completionPercentage() >= 40 ? '#ffc107' : '#dc3545') }};"
                                     aria-valuenow="{{ $team->completionPercentage() }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                    {{ number_format($team->completionPercentage(), 2) }}%
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <!-- Use Flexbox to center and align buttons -->
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <a href="{{ route('user.teams.show', $team->id) }}" class="btn btn-info btn-sm" title="View Team">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id)
                                    <a href="{{ route('user.teams.edit', $team->id) }}" class="btn btn-warning btn-sm" title="Edit Team">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('teams.destroy', $team->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteTeamModal" data-team-id="{{ $team->id }}" data-team-name="{{ $team->name }}" title="Delete Team">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No teams available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

            <!-- Card Layout -->
            <div id="card-view-container" style="display: none;">
                <div class="row">
                    @forelse ($teams as $team)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-lg rounded-lg border-0">
                                <div class="card-body d-flex flex-column">
                                    <div class="text-center mb-3 d-flex justify-content-center align-items-center">
                                        @if($team->image)
                                            <img src="{{ asset('storage/' . $team->image) }}" alt="{{ $team->name }}" 
                                                class="img-fluid rounded-circle mx-auto" 
                                                style="max-width: 80px;">
                                        @endif
                                    </div>
                                    <h5 class="card-title text-primary font-weight-bold text-center">{{ $team->name }}</h5>
                                    <div class="progress mb-3" style="height: 20px;">
                                        <div class="progress-bar"
                                            role="progressbar"
                                            style="width: {{ $team->completionPercentage() }}%;
                                                   background-color: {{ $team->completionPercentage() >= 70 ? '#28a745' : ($team->completionPercentage() >= 40 ? '#ffc107' : '#dc3545') }};"
                                            aria-valuenow="{{ $team->completionPercentage() }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                            {{ number_format($team->completionPercentage(), 2) }}%
                                        </div>
                                    </div>
                                    <p class="card-text text-center"><strong>Creator:</strong> {{ $team->creator->name }}</p>
                                    <p class="card-text text-center"><strong>Members:</strong> {{ $team->members->count() }}</p>
                                    <div class="mt-auto d-flex justify-content-between">
                                        <a href="{{ route('user.teams.show', $team->id) }}" class="btn btn-info flex-grow-1 mr-2" title="View Team">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        @if(Auth::user()->usertype == 'admin' || Auth::id() == $team->creator_id)
                                            <a href="{{ route('user.teams.edit', $team->id) }}" class="btn btn-warning flex-grow-1 mr-2" title="Edit Team">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </a>
                                            <form action="{{ route('user.teams.destroy', $team->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteTeamModal" data-team-id="{{ $team->id }}" data-team-name="{{ $team->name }}">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center">No teams available.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- Delete Team Modal -->
    <div class="modal fade" id="deleteTeamModal" tabindex="-1" role="dialog" aria-labelledby="deleteTeamModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteTeamModalLabel">Delete Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the team <strong id="teamName"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteTeamForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- External Scripts -->
    <script>
        $('#deleteTeamModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var teamId = button.data('team-id');
            var teamName = button.data('team-name');

            var modal = $(this);
            var form = modal.find('#deleteTeamForm');

            modal.find('#teamName').text(teamName);
            form.attr('action', '/user/teams/' + teamId);
        });

        document.getElementById('table-view-btn').addEventListener('click', function () {
            document.getElementById('table-view-container').style.display = 'block';
            document.getElementById('card-view-container').style.display = 'none';
        });

        document.getElementById('card-view-btn').addEventListener('click', function () {
            document.getElementById('table-view-container').style.display = 'none';
            document.getElementById('card-view-container').style.display = 'block';
        });

        $(document).ready(function () {
            $('#teams-table').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            });
        });
    </script>
</x-app-layout>
