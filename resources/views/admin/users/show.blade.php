<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-black-200 leading-tight">
            {{ __('User Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- User Info Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="card card-primary card-outline">
                    <div class="card-header text-center">
                        <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('dist/img/avatar5.png') }}"
                             class="rounded-circle mx-auto d-block mb-3"
                             alt="Profile Picture"
                             style="width: 120px; height: 120px; object-fit: cover;">
                        <h3 class="card-title">{{ $user->name }}'s Profile</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column (User Info) -->
                            <div class="col-md-6">
                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <b>Name:</b> <span class="float-right">{{ $user->name }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Full Name:</b> <span class="float-right">{{ $user->full_name }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Email:</b> <span class="float-right">{{ $user->email }}</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Right Column (Additional Info) -->
                            <div class="col-md-6">
                                <ul class="list-group list-group-unbordered mb-3">
                                    <li class="list-group-item">
                                        <b>About Me:</b> <span class="float-right">{{ $user->about_me ? $user->about_me : 'No information provided.' }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Skills:</b> 
                                        <span class="float-right">
                                            @if($user->skills)
                                                @php
                                                    $skills = json_decode($user->skills);
                                                @endphp
                                                @if(is_array($skills))
                                                    {{ implode(', ', $skills) }}
                                                @else
                                                    {{ $user->skills }}
                                                @endif
                                            @else
                                                No information provided.
                                            @endif
                                        </span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Education:</b> <span class="float-right">{{ $user->education ? $user->education : 'No information provided.' }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Bio:</b> <span class="float-right">{{ $user->bio ? $user->bio : 'No information provided.' }}</span>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Address:</b> <span class="float-right">{{ $user->address ? $user->address : 'No information provided.' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div> <!-- /.card -->
            </div>

            <!-- Task Details Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="card card-secondary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Task Details</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Total Tasks:</b> <span class="float-right">{{ $user->tasks_count }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>Tasks Not Started:</b> <span class="float-right">{{ $user->tasks_not_started_count }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>Tasks In Progress:</b> <span class="float-right">{{ $user->tasks_in_progress_count }}</span>
                            </li>
                            <li class="list-group-item">
                                <b>Tasks Done:</b> <span class="float-right">{{ $user->tasks_done_count }}</span>
                            </li>
                        </ul>
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                    </div>
                </div> <!-- /.card -->
            </div>
        </div>
    </div>
</x-app-layout>
