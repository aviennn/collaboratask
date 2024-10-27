@extends('layouts.app') <!-- Extending the main layout for consistency -->

@section('content')
    <div class="container">
        <!-- Feedback Header -->
        <h1 class="text-xl font-semibold mb-4 text-center text-purple-700 dark:text-purple-300">
            <i class="fas fa-comments"></i> User Feedback Center
        </h1>
        
        <!-- Feedback Table Container with Delete Option -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-md shadow mb-6">
            <table class="table-auto w-full text-center border-collapse">
                <thead class="bg-purple-600 text-white dark:bg-gray-700">
                    <tr>
                        <th class="py-3 px-4 border-b border-gray-200 text-sm font-semibold"><i class="fas fa-tag"></i> Category</th>
                        <th class="py-3 px-4 border-b border-gray-200 text-sm font-semibold"><i class="fas fa-user"></i> User</th>
                        <th class="py-3 px-4 border-b border-gray-200 text-sm font-semibold"><i class="fas fa-comment"></i> Comment</th>
                        <th class="py-3 px-4 border-b border-gray-200 text-sm font-semibold"><i class="fas fa-star"></i> Rating</th>
                        <th class="py-3 px-4 border-b border-gray-200 text-sm font-semibold"><i class="fas fa-calendar-alt"></i> Date</th>
                        <th class="py-3 px-4 border-b border-gray-200 text-sm font-semibold"><i class="fas fa-cog"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($feedbacks as $feedback)
                        <tr class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition duration-150 ease-in-out" id="feedback-row-{{ $feedback->id }}">
                            <!-- Category with Simple Badge -->
                            <td class="py-3 px-4 border-b text-gray-900 dark:text-gray-200">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold 
                                {{ $feedback->category == 'Bug' ? 'bg-red-300 text-red-900' : 
                                    ($feedback->category == 'Suggestion' ? 'bg-yellow-300 text-yellow-900' : 
                                    ($feedback->category == 'Feature Request' ? 'bg-green-300 text-green-900' : 
                                    'bg-blue-300 text-blue-900')) }} dark:bg-gray-700 dark:text-gray-200">
                                    <i class="fas {{ $feedback->category == 'Bug' ? 'fa-bug' : 
                                        ($feedback->category == 'Suggestion' ? 'fa-lightbulb' : 
                                        ($feedback->category == 'Feature Request' ? 'fa-tools' : 'fa-info-circle')) }} mr-1"></i>
                                    {{ $feedback->category }}
                                </span>
                            </td>

                            <!-- User Name with Icon -->
                            <td class="py-3 px-4 border-b text-gray-900 dark:text-gray-200">
                                <div class="flex items-center justify-center">
                                    <i class="fas fa-user mr-2 text-purple-500 dark:text-purple-400"></i>
                                    {{ $feedback->user->name }}
                                </div>
                            </td>

                            <!-- Comment Text -->
                            <td class="py-3 px-4 border-b text-gray-700 dark:text-gray-300">
                                {{ Str::limit($feedback->comment, 50, '...') }}
                            </td>

                            <!-- Star Rating -->
                            <td class="py-3 px-4 border-b text-yellow-500 dark:text-yellow-400">
                                @if($feedback->rating)
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $feedback->rating ? '' : 'text-gray-300 dark:text-gray-500' }}"></i>
                                    @endfor
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">N/A</span>
                                @endif
                            </td>

                            <!-- Feedback Date -->
                            <td class="py-3 px-4 border-b text-gray-600 dark:text-gray-400">
                                {{ $feedback->created_at->format('M d, Y H:i') }}
                            </td>

                            <!-- Delete Button for Admin with AJAX -->
                            <td class="py-3 px-4 border-b text-gray-600 dark:text-gray-400">
                                <button class="px-2 py-1 bg-red-500 hover:bg-red-600 text-white rounded-md delete-feedback" data-id="{{ $feedback->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Simple Pagination -->
            <div class="mt-4 flex justify-center">
                {{ $feedbacks->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- AJAX Delete Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Attach click event to delete buttons
            document.querySelectorAll('.delete-feedback').forEach(button => {
                button.addEventListener('click', function () {
                    const feedbackId = this.getAttribute('data-id');
                    if (confirm('Are you sure you want to delete this feedback?')) {
                        // Make the AJAX request to delete feedback
                        fetch(`/feedback/${feedbackId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the feedback row from the table
                                document.getElementById(`feedback-row-${feedbackId}`).remove();
                            } else {
                                alert('Failed to delete feedback.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred.');
                        });
                    }
                });
            });
        });
    </script>
@endsection
