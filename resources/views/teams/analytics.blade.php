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
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                
                <!-- Task Breakdown -->
                <div class="bg-white shadow-md rounded-lg p-4 transition-all transform hover:scale-105 duration-200">
                    <h6 class="text-lg font-bold text-gray-700 mb-4">Task Breakdown</h6>
                    <canvas id="taskChart" class="max-h-48"></canvas>
                </div>

                <!-- Task Priorities Breakdown -->
                <div class="bg-white shadow-md rounded-lg p-4 transition-all transform hover:scale-105 duration-200">
                    <h6 class="text-lg font-bold text-gray-700 mb-4">Task Priorities Breakdown</h6>
                    <canvas id="priorityChart" class="max-h-48"></canvas>
                </div>

                <!-- Task Completion and Time Statistics -->
                <div class="bg-white shadow-md rounded-lg p-4 transition-all transform hover:scale-105 duration-200">
                    <h6 class="text-lg font-bold text-gray-700 mb-4">Task Completion and Time Statistics</h6>
                    <div class="text-gray-600 text-sm">
                        <p><strong>Tasks Completed On Time:</strong> {{ $tasksCompletedOnTime }}</p>
                        <p><strong>Overdue Tasks:</strong> {{ $tasksOverdue }}</p>
                        <p><strong>Task Completion Rate:</strong> {{ number_format($completionRate, 2) }}%</p>
                        <p><strong>Average Time to Complete Tasks:</strong> {{ $averageCompletionTime ?? 'N/A' }} hours</p>
                    </div>
                </div>

                <!-- Grading Breakdown -->
                @if ($team->has_rewards)
                <div class="bg-white shadow-md rounded-lg p-4 transition-all transform hover:scale-105 duration-200">
                    <h6 class="text-lg font-bold text-gray-700 mb-4">Grading Breakdown</h6>
                    <canvas id="gradingChart" class="max-h-48"></canvas>
                </div>
                @endif

                 <!-- Redeemed Rewards Section -->
                 <div class="bg-white shadow-md rounded-lg p-4 transition-all transform hover:scale-105 duration-200">
                    <h6 class="text-lg font-bold text-gray-700 mb-4">Redeemed Rewards</h6>
                    <ul class="list-disc pl-5">
                        @forelse($redeemedRewards as $reward)
                            <li class="text-sm text-gray-600">
                                {{ $reward->name }} - {{ $reward->description }} 
                                ({{ $reward->points_required }} points)
                            </li>
                        @empty
                            <li class="text-sm text-gray-600">No rewards redeemed yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Task Breakdown Chart -->
    <script>
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
                            color: '#4a5568'
                        }
                    }
                }
            }
        });
    </script>

    <!-- Task Priorities Breakdown Chart -->
    <script>
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
                            color: '#4a5568'
                        }
                    }
                }
            }
        });
    </script>

    <!-- Grading Breakdown Chart -->
    @if ($team->has_rewards)
    <script>
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
                            color: '#e2e8f0'
                        },
                        ticks: {
                            color: '#4a5568',
                            font: {
                                size: 12,
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: '#e2e8f0'
                        },
                        ticks: {
                            color: '#4a5568',
                            font: {
                                size: 12,
                                family: "'Inter', sans-serif"
                            }
                        }
                    }
                }
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
