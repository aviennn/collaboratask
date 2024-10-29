@extends('layouts.app') <!-- Extending the main layout for consistency -->

@section('content')
<div class="container">
    <h1 class="text-xl font-semibold mb-4 text-center text-purple-700 dark:text-purple-300">
        <i class="fas fa-comments"></i> User Feedback Center
    </h1>

    <!-- Feedback Table Container with Delete Option -->
    <div class="card card-primary card-outline">
        <div class="card-body p-0">
            <div class="table-responsive"> <!-- Responsive wrapper -->
                <table class="table table-bordered table-hover text-center align-middle">
                    <thead class="bg-purple-600 text-white">
                        <tr>
                            <th><i class="fas fa-tag"></i> Category</th>
                            <th><i class="fas fa-user"></i> User</th>
                            <th><i class="fas fa-comment"></i> Comment</th>
                            <th><i class="fas fa-star"></i> Rating</th>
                            <th><i class="fas fa-calendar-alt"></i> Date</th>
                            <th><i class="fas fa-cog"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($feedbacks as $feedback)
                             <tr id="feedback-{{ $feedback->id }}" class="{{ $highlightFeedbackId == $feedback->id ? 'table-warning' : '' }}">
                                <td class="align-middle">
                                    <span class="badge rounded-pill 
                                        {{ $feedback->category == 'Bug' ? 'bg-danger' : 
                                        ($feedback->category == 'Suggestion' ? 'bg-warning text-dark' : 
                                        ($feedback->category == 'Feature Request' ? 'bg-success' : 
                                        'bg-info text-dark')) }}">
                                        <i class="fas {{ $feedback->category == 'Bug' ? 'fa-bug' : 
                                            ($feedback->category == 'Suggestion' ? 'fa-lightbulb' : 
                                            ($feedback->category == 'Feature Request' ? 'fa-tools' : 'fa-info-circle')) }} mr-1"></i>
                                        {{ $feedback->category }}
                                    </span>
                                </td>

                                <td class="align-middle">{{ $feedback->user->name }}</td>

                                <td class="align-middle">
                                    <span class="d-flex justify-content-between align-items-center">
                                        {{ Str::limit($feedback->comment, 30, '...') }}
                                        <a href="javascript:void(0);" class="ml-1 text-primary view-comment" data-comment="{{ $feedback->comment }}">
                                            View
                                        </a>
                                    </span>
                                </td>

                                <td class="align-middle">
                                    @if($feedback->rating)
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-warning' : 'text-secondary' }}"></i>
                                        @endfor
                                    @else
                                        <span class="text-secondary">N/A</span>
                                    @endif
                                </td>

                                <td class="align-middle">{{ $feedback->created_at->format('M d, Y H:i') }}</td>

                                <td class="align-middle">
                                    <button class="btn btn-danger delete-feedback" data-id="{{ $feedback->id }}">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer">
                {{ $feedbacks->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- AdminLTE Modal for Full Comment View -->
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="commentModalLabel">Full Comment</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modal-comment" class="text-dark"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<style>
    .table-warning {
    background-color: #ffeeba !important; /* Example background color */
}
</style>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const feedbackId = '{{ $highlightFeedbackId }}';
        if (feedbackId) {
            const feedbackRow = document.getElementById(`feedback-${feedbackId}`);
            if (feedbackRow) {
                feedbackRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Delete feedback with AJAX
document.querySelectorAll('.delete-feedback').forEach(button => {
    button.addEventListener('click', function () {
        const feedbackId = this.getAttribute('data-id');
        if (confirm('Are you sure you want to delete this feedback?')) {
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
                    // Corrected ID to match HTML structure
                    document.getElementById(`feedback-${feedbackId}`).remove();
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
        // Show the modal with the full comment
        document.querySelectorAll('.view-comment').forEach(button => {
            button.addEventListener('click', function () {
                const comment = this.getAttribute('data-comment');
                document.getElementById('modal-comment').innerText = comment;
                $('#commentModal').modal('show'); // Use AdminLTE's modal show method
            });
        });
    });
</script>
@endsection
