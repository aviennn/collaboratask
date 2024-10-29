<x-app-layout>
    <x-slot name="header">
        <div class="relative w-full">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Analytics for ' . $user->name) }}
            </h2>
            <p class="text-md text-gray-700">
                {{ __('Email: ') }} <span class="font-semibold">{{ $user->email }}</span>
            </p>

            <!-- Total Points Earned at the top-right -->
            @if ($team->has_rewards)
            <div class="absolute top-0 right-0 w-40 text-gray-800 bg-white border border-gray-200 px-4 py-2 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-center space-x-1">
                    <i class="fas fa-star text-yellow-400 text-xl"></i>
                    <p id="totalPoints" class="text-2xl font-extrabold text-gray-800">
                        {{ $totalPoints > 0 ? $totalPoints : 0 }}
                    </p>
                </div>
                <p class="text-sm font-medium text-gray-600 text-center mt-1">Level {{ $user->level ?? 'N/A' }}</p>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                    <div class="bg-yellow-400 h-1.5 rounded-full" style="width: {{ $progressPercentage ?? '50%' }};"></div>
                </div>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto">
            <!-- User Profile & Badges Sidebar -->
            <div class="row">
    <!-- Left Column: Profile Information -->
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-body text-center">
                <!-- Profile Picture with Border -->
                <div class="profile-picture-container position-relative mb-4">
                    @php
                        // Check and assign the correct border image
                        if ($user->selected_border === 'sample-1') {
                            $borderImage = asset('images/sample-borders/border-sample-1.png');
                        } elseif ($user->selected_border === 'sample-2') {
                            $borderImage = asset('images/sample-borders/border-sample-2.png');
                        } else {
                            $activeBorder = $user->borders->where('pivot.is_active', 1)->first();
                            $borderImage = $activeBorder ? asset($activeBorder->image) : asset('images/placeholder/border-placeholder.png');
                        }
                    @endphp

                    <!-- Profile Border & Picture -->
                    <div class="border-wrapper position-relative" style="width: 150px; height: 150px; margin: 0 auto;">
                        <img src="{{ $borderImage }}" 
                             class="profile-border rounded-circle position-absolute" 
                             style="width: 150px; height: 150px; top: 0; left: 0; z-index: 1;" 
                             alt="Profile Border">

                        <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('dist/img/avatar5.png') }}" 
                             class="profile-picture rounded-circle object-cover position-absolute" 
                             style="width: 110px; height: 110px; top: 20px; left: 20px; border: 3px solid #fff; z-index: 2;" 
                             alt="Profile Picture">
                    </div>
                </div>
                
                <h3 class="font-weight-bold mt-4">{{ $user->name }}</h3>
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

                <p class="text-black mt-3"><strong>Bio:</strong> {{ Auth::user()->bio ?? '' }}</p>
             <!--    <p><strong>Email:</strong> {{ $user->email }}</p>
               

               <a href="{{ route('profile.edit') }}" class="btn btn-info mt-3">Edit Profile</a>-->
            </div>
        </div>

        <!-- Unlocked Badges Section -->
        <div class="card card-secondary card-outline shadow-sm mt-4">
            <div class="card-body">
                <h5 class="text-center"><b>Unlocked Badges</b></h5>
                <div id="badgeCarousel" class="carousel slide mb-3" data-ride="carousel" data-interval="3000">
                    <div class="carousel-inner">
                        @php
                            $chunkSize = 2; // Display 2 badges per slide
                        @endphp
                        @forelse ($user->badges->chunk($chunkSize) as $index => $badgeChunk)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                <div class="d-flex justify-content-around">
                                    @foreach ($badgeChunk as $badge)
                                        <div class="badge-item text-center">
                                            <img src="{{ asset($badge->icon) }}" 
                                                 alt="{{ $badge->name }}" 
                                                 class="badge-icon rounded-circle mb-2" 
                                                 style="width: 60px; height: 60px;">
                                            <p class="font-weight-bold mb-0">{{ $badge->name }}</p>
                                            <p class="text-muted small">{{ $badge->description }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-center">No badges unlocked yet.</p>
                        @endforelse
                    </div>
                    <a class="carousel-control-prev custom-carousel-control" href="#badgeCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next custom-carousel-control" href="#badgeCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Analytics Data -->
    <div class="col-lg-8 col-md-12 mb-4">
        <div class="row">
            <!-- Task Breakdown -->
            <div class="col-md-6 mb-4">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Task Breakdown</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="taskChart" style="max-height: 200px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Task Priorities Breakdown -->
            <div class="col-md-6 mb-4">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Task Priorities Breakdown</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="priorityChart" style="max-height: 200px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Task Completion and Time Statistics -->
            <div class="col-md-6 mb-4">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Task Completion and Time Statistics</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-muted">
                            <p><strong>Tasks Completed On Time:</strong> {{ $tasksCompletedOnTime }}</p>
                            <p><strong>Overdue Tasks:</strong> {{ $tasksOverdue }}</p>
                            <p><strong>Task Completion Rate:</strong> {{ number_format($completionRate, 2) }}%</p>
                            <p><strong>Average Time to Complete Tasks:</strong> {{ $averageCompletionTime ?? 'N/A' }} hours</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grading Breakdown -->
            @if ($team->has_rewards)
                <div class="col-md-6 mb-4">
                    <div class="card card-outline card-primary shadow-sm">
                        <div class="card-header">
                            <h3 class="card-title">Grading Breakdown</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="gradingChart" style="max-height: 200px;"></canvas>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
    </div>

    <style>
        /* Position the arrows closer to the center vertically and ensure they are visible */
.carousel-control-prev,
.carousel-control-next {
    top: 50%; /* Center vertically */
    transform: translateY(-50%); /* Adjust for exact centering */
    width: 40px; /* Adjust the arrow width if needed */
    height: 40px; /* Adjust the arrow height if needed */
    background-color: rgba(0, 0, 0, 0.5); /* Ensure a dark semi-transparent background for visibility */
    border-radius: 50%; /* Make it a circular button */
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Make the arrows more visible in light mode */
body.light-mode .carousel-control-prev-icon,
body.light-mode .carousel-control-next-icon {
    filter: invert(1); /* Inverts color to make it visible on light background */
}

/* Adjust arrow icon size and visibility */
.carousel-control-prev-icon,
.carousel-control-next-icon {
    background-size: 20px 20px; /* Control icon size */
    background-color: transparent; /* Remove background to keep transparency */
}

/* Add hover effect for better visibility */
.carousel-control-prev:hover,
.carousel-control-next:hover {
    background-color: rgba(0, 0, 0, 0.7); /* Darker on hover */
}

/* Optional: Additional styling for dark mode */
body.dark-mode .carousel-control-prev,
body.dark-mode .carousel-control-next {
    background-color: rgba(255, 255, 255, 0.3); /* Lighter for dark mode */
}

body.dark-mode .carousel-control-prev:hover,
body.dark-mode .carousel-control-next:hover {
    background-color: rgba(255, 255, 255, 0.5); /* Lighter on hover for dark mode */
}

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
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

   <!-- Task Breakdown Chart -->
<script>
    // Function to get the correct font color based on mode
    function getFontColor() {
        return document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#4a5568';
    }

    // Initialize Task Breakdown Chart
    var taskCtx = document.getElementById('taskChart').getContext('2d');
    var taskChart = new Chart(taskCtx, {
        type: 'pie',
        data: {
            labels: ['Completed', 'In Progress', 'Not Started'],
            datasets: [{
                data: [{{ $completedTasks }}, {{ $inProgressTasks }}, {{ $notStartedTasks }}],
                backgroundColor: ['#38b2ac', '#f6ad55', '#fc8181'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif"
                        },
                        color: getFontColor() // Use dynamic font color
                    }
                }
            }
        }
    });

    // Update chart when the mode changes
    document.addEventListener('DOMContentLoaded', function () {
        const darkModeToggle = document.querySelector('#darkModeToggle');
        if (darkModeToggle) {
            darkModeToggle.addEventListener('change', function () {
                taskChart.options.plugins.legend.labels.color = getFontColor();
                taskChart.update();
            });
        }
    });
</script>


   <!-- Task Priorities Breakdown Chart -->
<script>
    // Initialize Task Priorities Breakdown Chart
    var priorityCtx = document.getElementById('priorityChart').getContext('2d');
    var priorityChart = new Chart(priorityCtx, {
        type: 'doughnut',
        data: {
            labels: ['High Priority', 'Medium Priority', 'Low Priority'],
            datasets: [{
                data: [{{ $highPriorityTasks }}, {{ $mediumPriorityTasks }}, {{ $lowPriorityTasks }}],
                backgroundColor: ['#e53e3e', '#ed8936', '#38a169'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif"
                        },
                        color: getFontColor() // Use dynamic font color
                    }
                }
            }
        }
    });

    // Update chart when the mode changes
    document.addEventListener('DOMContentLoaded', function () {
        const darkModeToggle = document.querySelector('#darkModeToggle');
        if (darkModeToggle) {
            darkModeToggle.addEventListener('change', function () {
                priorityChart.options.plugins.legend.labels.color = getFontColor();
                priorityChart.update();
            });
        }
    });
</script>


    <!-- Grading Breakdown Chart -->
@if ($team->has_rewards)
<script>
    // Initialize Grading Breakdown Chart
    var gradingCtx = document.getElementById('gradingChart').getContext('2d');
    var gradingChart = new Chart(gradingCtx, {
        type: 'bar',
        data: {
            labels: ['Good', 'Very Good', 'Excellent'],
            datasets: [{
                label: 'Number of Tasks',
                data: [{{ $countGood }}, {{ $countVeryGood }}, {{ $countExcellent }}],
                backgroundColor: ['#63b3ed', '#f6e05e', '#48bb78'],
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: document.body.classList.contains('dark-mode') ? '#4a5568' : '#e2e8f0'
                    },
                    ticks: {
                        color: getFontColor(), // Use dynamic font color
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif"
                        }
                    }
                },
                x: {
                    grid: {
                        color: document.body.classList.contains('dark-mode') ? '#4a5568' : '#e2e8f0'
                    },
                    ticks: {
                        color: getFontColor(), // Use dynamic font color
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif"
                        }
                    }
                }
            }
        }
    });

    // Update chart when the mode changes
    document.addEventListener('DOMContentLoaded', function () {
        const darkModeToggle = document.querySelector('#darkModeToggle');
        if (darkModeToggle) {
            darkModeToggle.addEventListener('change', function () {
                // Update the scales colors dynamically based on mode
                gradingChart.options.scales.x.ticks.color = getFontColor();
                gradingChart.options.scales.y.ticks.color = getFontColor();
                gradingChart.options.scales.x.grid.color = document.body.classList.contains('dark-mode') ? '#4a5568' : '#e2e8f0';
                gradingChart.options.scales.y.grid.color = document.body.classList.contains('dark-mode') ? '#4a5568' : '#e2e8f0';
                gradingChart.update();
            });
        }
    });
</script>


    @endif

    <!-- Count-Up Animation for Total Points -->
    <script>
function animateValue(id, start, end, duration) {
    var obj = document.getElementById(id);
    if (end < 0) end = 0;  // Ensure that the final value is never below 0
    var range = end - start;
    var current = start;
    var increment = end > start ? 1 : -1;
    var stepTime = Math.abs(Math.floor(duration / range));
    
    var timer = setInterval(function() {
        current += increment;
        obj.innerHTML = current;
        if (current == end || current < 0) {  // Ensure that it stops at 0 or the end
            clearInterval(timer);
        }
    }, stepTime);
}
    </script>

</x-app-layout>
