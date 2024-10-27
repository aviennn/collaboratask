@extends('layouts.app') <!-- Extending the main layout -->

@section('content')
    <div class="container mx-auto">
        <!-- Form Header with Icon -->
        <h1 class="text-lg font-bold mb-4 text-center text-purple-600 dark:text-purple-300">
            <i class="fas fa-gamepad mr-1"></i> Submit General Feedback
        </h1>

        <!-- Success Message (Initially Hidden) -->
        <div id="success-message" class="alert alert-success flex items-center justify-center bg-green-100 border border-green-400 text-green-700 px-2 py-2 rounded hidden" role="alert">
            <i class="fas fa-check-circle mr-1"></i>
            <span>Feedback submitted successfully!</span>
        </div>

        <!-- Feedback Form -->
        <form id="feedback-form" action="{{ route('feedback.store') }}" method="POST" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md space-y-4">
            @csrf

            <!-- Hidden input to store selected category -->
            <input type="hidden" name="category" id="selected-category" required>

            <!-- Category Selection Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <!-- Category Cards -->
                @foreach ([
                    ['Bug', 'fa-bug', 'text-red-600 dark:text-red-500', 'For reporting bugs or errors.'],
                    ['Suggestion', 'fa-lightbulb', 'text-yellow-500 dark:text-yellow-400', 'Share your suggestions.'],
                    ['Feature Request', 'fa-tools', 'text-green-600 dark:text-green-500', 'Request a new feature.'],
                    ['Other', 'fa-comment-dots', 'text-blue-500 dark:text-blue-400', 'Anything else.'],
                ] as [$title, $icon, $colorClass, $description])
                    <div class="category-card bg-white dark:bg-gray-700 hover:shadow-md rounded-md p-3 cursor-pointer transition transform hover:scale-105" 
                        data-category="{{ $title }}" 
                        onclick="selectCategory('{{ $title }}', this)">
                        <div class="flex items-center justify-center mb-2">
                            <i class="fas {{ $icon }} {{ $colorClass }} text-3xl"></i>
                        </div>
                        <h2 class="text-center font-semibold {{ $colorClass }}">{{ $title }}</h2>
                        <p class="text-center {{ $colorClass }} text-sm mt-1">{{ $description }}</p>
                    </div>
                @endforeach
            </div>

            <!-- Comment Section with Text Area -->
            <div>
                <label for="comment" class="block text-sm font-medium text-black dark:text-indigo-200 mb-1">
                    <i class="fas fa-comment"></i> Your Feedback
                </label>
                <textarea name="comment" id="comment" rows="3" placeholder="Tell us what you think..." 
    class="w-full px-2 py-1 rounded-md border border-gray-300 bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200" 
    required></textarea>

            </div>

            <!-- Rating Selection (Stars) -->
            <div>
                <label for="rating" class="block text-sm font-medium text-black dark:text-indigo-200 mb-1">
                    <i class="fas fa-star"></i> Rating (Optional)
                </label>
                <select name="rating" id="rating" class="w-full px-2 py-1 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-gray-200">
                    <option value="">Select Rating</option>
                    <option value="1">⭐ 1 Star</option>
                    <option value="2">⭐⭐ 2 Stars</option>
                    <option value="3">⭐⭐⭐ 3 Stars</option>
                    <option value="4">⭐⭐⭐⭐ 4 Stars</option>
                    <option value="5">⭐⭐⭐⭐⭐ 5 Stars</option>
                </select>
            </div>

            <!-- Submit Button with Gamified Style -->
            <div class="text-center">
                <button type="submit" class="mt-3 px-5 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white font-semibold rounded-full shadow transform hover:scale-105 transition duration-150 ease-in-out">
                    <i class="fas fa-paper-plane mr-1"></i> Submit Feedback
                </button>
            </div>
        </form>
    </div>

    <!-- JavaScript for AJAX Form Submission and Card Selection -->
    <script>
        // Category Selection Function
        function selectCategory(category, element) {
            // Set the hidden input with the selected category value
            document.getElementById('selected-category').value = category;

            // Remove the 'selected' style from all cards
            const cards = document.querySelectorAll('.category-card');
            cards.forEach(card => {
                card.classList.remove('bg-blue-500', 'text-white', 'shadow-lg');
                card.classList.add('bg-white', 'dark:bg-gray-700');
            });

            // Add the 'selected' style to the clicked card
            element.classList.remove('bg-white', 'dark:bg-gray-700');
            element.classList.add('bg-blue-500', 'text-white', 'shadow-lg');
        }

        // AJAX Form Submission
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('feedback-form');

            form.addEventListener('submit', function (event) {
                event.preventDefault(); // Prevent the form from submitting the normal way

                // Collect form data
                const formData = new FormData(form);

                // AJAX request
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        document.getElementById('success-message').classList.remove('hidden');
                        // Optionally, reset the form fields
                        form.reset();
                        // Remove the selected state from category cards
                        const cards = document.querySelectorAll('.category-card');
                        cards.forEach(card => {
                            card.classList.remove('bg-blue-500', 'text-white', 'shadow-lg');
                            card.classList.add('bg-white', 'dark:bg-gray-700');
                        });
                    } else {
                        alert('Failed to submit feedback.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting feedback.');
                });
            });
        });
    </script>
@endsection
