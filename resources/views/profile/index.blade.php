<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="row">
                <!-- Left Sidebar: Profile Picture & Info -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <!-- Profile Picture with Border -->
<div class="profile-picture-container position-relative mb-4">
    @php
        // If the user has selected a sample border, use that image
        if ($user->selected_border === 'sample-1') {
            $borderImage = asset('images/sample-borders/border-sample-1.png');
        } elseif ($user->selected_border === 'sample-2') {
            $borderImage = asset('images/sample-borders/border-sample-2.png');
        } else {
            // Get the active border, if available, otherwise use a placeholder
            $activeBorder = $user->borders->where('pivot.is_active', 1)->first();
            $borderImage = $activeBorder ? asset($activeBorder->image) : asset('images/placeholder/border-placeholder.png');
        }
    @endphp

    <!-- Profile Border & Picture -->
    <!-- Profile Border & Picture -->
<div class="border-wrapper position-relative" style="width: 150px; height: 150px; margin: 0 auto;">
    <!-- Profile Border (centered over profile picture) -->
    <img src="{{ $borderImage }}" 
         class="profile-border rounded-circle position-absolute" 
         style="width: 150px; height: 150px; top: 0; left: 0; z-index: 1;" 
         alt="Profile Border">

    <!-- Profile Picture (centered inside the border) -->
    <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('dist/img/avatar5.png') }}" 
         class="profile-picture rounded-circle object-cover position-absolute" 
         style="width: 110px; height: 110px; top: 20px; left: 20px; border: 3px solid #fff; z-index: 2;" 
         alt="Profile Picture">
</div>

</div>
        
                            
                            <h3 class="font-weight-bold mt-4">{{ $user->name }}</h3>
                            <!--<p class="text-muted">{{ $user->usertype ?? 'user' }}</p>-->
                            <div class="title-display mt-3">
                                <h4 class="font-weight-bold">{{ $user->selectedTitle->name ?? 'No Title Selected' }}</h4>
                                <p class="text-muted">{{ $user->selectedTitle->description ?? '' }}</p>
                            </div>
                            <!-- XP and Level Info with Progress Bar -->
                            <div class="user-xp-level mt-3">
                                <p>Level: <span class="badge badge-primary">{{ auth()->user()->level }}</span></p>
                                <div class="progress my-2" style="height: 20px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                                         role="progressbar" 
                                         style="width: {{ (auth()->user()->xp / 100) * 100 }}%;" 
                                         aria-valuenow="{{ auth()->user()->xp }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ (auth()->user()->xp / 100) * 100 }}%
                                    </div>
                                </div>
                                <p>XP: {{ auth()->user()->xp }} / 100 XP</p>
                                <p>XP Until Next Level: {{ auth()->user()->xpUntilNextLevel() }} XP</p>
                            </div>

                            <!-- User Info -->
                            <p class="text-gray-700 mt-3"><strong>Bio:</strong> {{ Auth::user()->bio ?? '' }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Address:</strong> {{ $user->address ?? 'Not provided' }}</p>

                            <a href="{{ route('profile.edit') }}" class="btn btn-info mt-3">Edit Profile</a>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar: Tabs & Badge Carousel -->
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <!-- Badge Carousel -->
                            <h4 class="text-center"><b>Unlocked Badges</b></h4>
                            <div id="badgeCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @forelse ($user->badges->chunk(3) as $index => $badgeChunk)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <div class="d-flex justify-content-around">
                                                @foreach ($badgeChunk as $badge)
                                                    <div class="badge-item text-center">
                                                        <img src="{{ asset($badge->icon) }}" 
                                                             alt="{{ $badge->name }}" 
                                                             class="badge-icon rounded-circle mb-2" 
                                                             style="width: 70px; height: 70px;">
                                                        <p class="font-weight-bold">{{ $badge->name }}</p>
                                                        <p class="text-muted small">{{ $badge->description }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center">No badges unlocked yet.</p>
                                    @endforelse
                                </div>

                                <!-- Carousel Controls -->
                                <a class="carousel-control-prev" href="#badgeCarousel" role="button" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#badgeCarousel" role="button" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>

                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="details-tab" data-bs-toggle="tab" href="#details" role="tab">User Details</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="border-tab" data-bs-toggle="tab" href="#border" role="tab">Border Selection</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="title-tab" data-bs-toggle="tab" href="#title-selection" role="tab">Title Selection</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="activity-tab" data-bs-toggle="tab" href="#activity" role="tab">Activity Log</a>
                                </li>
                            </ul>

                            <!-- Tab Contents -->
                            <div class="tab-content mt-3" id="profileTabContent">
                                <!-- User Details Tab -->
                                <div class="tab-pane fade show active" id="details" role="tabpanel">
                                    <h4><b>About Me</b></h4>
                                    <p>{{ $user->about_me ?? 'No information provided.' }}</p>

                                    <h4 class="mt-3"><b>Skills</b></h4>
                                    @if($user->skills)
                                        <ul>
                                            @foreach(json_decode($user->skills) as $skill)
                                                <li>{{ $skill }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>No skills added.</p>
                                    @endif

                                    <h4 class="mt-3"><b>Education</b></h4>
                                    <p>{{ $user->education ?? 'No information provided.' }}</p>
                                </div>

                                <!-- Border Selection Section -->
                                <div class="tab-pane fade" id="border" role="tabpanel">
                                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                                        <div class="p-6 bg-white border-b border-gray-200">
                                            <h3 class="text-lg font-semibold text-gray-900">Select Your Profile Border</h3>
                                            <form action="{{ route('profile.update-border') }}" method="POST">
                                                @csrf

                                                <div class="border-list flex gap-6 mt-4">
                                                    <!-- User's Unlocked Borders -->
                                                    @foreach($user->borders as $border)
                                                        <label class="cursor-pointer border-selection-item">
                                                            <input type="radio" name="border_id" value="{{ $border->id }}" {{ $border->pivot->is_active ? 'checked' : '' }} class="hidden">
                                                            <div class="border-item flex flex-col items-center justify-center">
                                                                <img src="{{ asset($border->image) }}" class="w-16 h-16 rounded-full {{ $border->pivot->is_active ? 'ring-4 ring-blue-500' : '' }}" alt="{{ $border->name }}">
                                                                <p class="mt-2">{{ $border->name }}</p>
                                                            </div>
                                                        </label>
                                                    @endforeach

                                                    <!-- Sample Borders -->
                                                    <label class="cursor-pointer border-selection-item">
                                                        <input type="radio" name="border_id" value="sample-1" class="hidden">
                                                        <div class="border-item flex flex-col items-center justify-center">
                                                            <img src="{{ asset('images/sample-borders/border-sample-1.png') }}" class="w-16 h-16 rounded-full" alt="Sample Border 1">
                                                            <p class="mt-2">Sample Border 1</p>
                                                        </div>
                                                    </label>

                                                    <label class="cursor-pointer border-selection-item">
                                                        <input type="radio" name="border_id" value="sample-2" class="hidden">
                                                        <div class="border-item flex flex-col items-center justify-center">
                                                            <img src="{{ asset('images/sample-borders/border-sample-2.png') }}" class="w-16 h-16 rounded-full" alt="Sample Border 2">
                                                            <p class="mt-2">Sample Border 2</p>
                                                        </div>
                                                    </label>
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="mt-4">
                                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Update Border</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                        <!-- Title Selection Tab -->
                                <div class="tab-pane fade" id="title-selection" role="tabpanel">
                                    <h4 class="font-weight-bold mb-4">Choose a Title</h4>
                                    
                                    <div class="row">
    @foreach($titles as $title)
        <div class="col-md-4 mb-3">
            <div class="card h-100 {{ $user->selected_title_id == $title->id ? 'border-primary' : '' }}">
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">
                    @if($title->icon)
                        <!-- Center the icon using Flexbox and ensure consistent sizing -->
                        <div class="icon-container mb-3 d-flex justify-content-center align-items-center">
                            <img src="{{ asset($title->icon) }}" alt="{{ $title->name }}" class="rounded-circle" style="width: 60px; height: 60px; object-fit: contain;">
                        </div>
                    @endif
                    <h5 class="card-title">{{ $title->name }}</h5>
                    <p class="card-text text-muted">{{ $title->description }}</p>
                    
                    <!-- Form for selecting the title -->
                    <form action="{{ route('titles.select') }}" method="POST">
                        @csrf
                        <input type="hidden" name="title_id" value="{{ $title->id }}">
                        <button type="submit" class="btn btn-sm btn-primary mt-2">
                            {{ $user->selected_title_id == $title->id ? 'Selected' : 'Select' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>

                                </div>
                                                        
                               <!-- Activity Log Tab -->
<div class="tab-pane fade" id="activity" role="tabpanel">
    <div id="activity-log-container">
        <!-- Replace the div with a table for DataTables -->
        <div class="table-responsive">
            <table class="table table-bordered" id="activityLogTable">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activities as $activity)
                        <tr>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->created_at->format('M d, Y H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
    .badge-item {
        display: flex;
        flex-direction: column;
        align-items: center; /* Center the content horizontally */
        justify-content: center; /* Center the content vertically if needed */
        text-align: center; /* Center text inside the container */
        padding: 10px;
        margin: 10px;
    }

    .badge-icon {
        display: block;
        margin: 0 auto;  /* Center the image horizontally */
        border-radius: 50%;
        border: 2px solid #ddd;
        padding: 5px;
    }
</style>

    <!-- Include Bootstrap JS for the carousel and tabs -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
   <!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize DataTables for the activity log table
        $('#activityLogTable').DataTable({
            "paging": true,        // Enable pagination
            "pageLength": 10,      // Show 10 entries per page
            "lengthChange": false, // Disable changing the page length
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

    <script>
$(document).ready(function() {
    // Handle pagination click
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();

        // Get the page number from the URL
        let page = $(this).attr('href').split('page=')[1];
        if (page) {
            fetchActivities(page);
        }
    });

    // Fetch activities for the specified page
    function fetchActivities(page) {
        $.ajax({
            url: '?page=' + page,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                console.log('Fetching activities for page: ' + page);
            },
            success: function(data) {
                // Update the activity log content
                $('#activity-log-container').html(data.activities);

                // Update pagination
                $('.pagination-container').html(data.pagination);

                // Scroll back to the top of the activities list if needed
                $('html, body').animate({ scrollTop: $('#activity').offset().top }, 'slow');
            },
            error: function(xhr) {
                console.error("Error fetching activities: ", xhr.responseText);
            }
        });
    }
});
</script>
</x-app-layout>
