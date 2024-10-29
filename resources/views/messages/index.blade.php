@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Team List (left side) -->
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h5 class="card-title text-center">Your Teams</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="nav nav-pills flex-column team-list" style="overflow-y: auto; max-height: 80vh;">
                        @foreach($userTeams as $team)
                            <li class="nav-item">
                                <a href="{{ route('messages.index', $team->id) }}" 
                                   class="nav-link team-link d-flex justify-content-between align-items-center"
                                   data-team-id="{{ $team->id }}">
                                    <i class="fas fa-users mr-2 text-primary"></i> {{ $team->name }}
                                    <span class="badge badge-danger">{{ $team->messages->count() }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Chat View (right side) -->
        <div class="col-md-8" id="chat-container">
            <div class="card card-primary">
                <div class="card-header">
                    <h4 class="card-title text-center">Select a team to view messages.</h4>
                </div>
                <div class="card-body chat-body">
                    <!-- Chat messages will load here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
     // Pass the authenticated user's profile photo URL to JavaScript
     var authUserAvatar = @json(Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('dist/img/avatar5.png'));
    $(document).ready(function () {
        console.log('Document ready!');

        // Function to scroll the chat list to the bottom
        function scrollToBottom() {
            var chatMessagesList = $('.chat-messages-list');
            if (chatMessagesList.length > 0) {
                chatMessagesList.scrollTop(chatMessagesList[0].scrollHeight);
            }
        }

        // Function to handle form submission via AJAX
        function attachFormSubmission(teamId) {
            $('#chat-form').off('submit').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                var message = $('#message-input').val(); // Get the message input value

                if (message.trim() !== '') { // Ensure the message isn't empty
                    $.post('/teams/' + teamId + '/messages', {
                        message: message,
                        _token: $('input[name="_token"]').val()
                    }, function(data) {
                        console.log('Message sent:', data);  // Log the response to ensure success

                        // Append the message sent by the user (sender)
                        appendMessageToChat(teamId, { message_body: data.message, sender_name: 'You' }, true);

                        // Clear the input field
                        $('#message-input').val('');

                        // Scroll to the bottom of the chat
                        scrollToBottom();
                    }).fail(function(xhr) {
                        alert('Error: ' + xhr.responseText); // Handle error
                    });
                }
            });
        }

       // Function to append messages to chat dynamically
       function appendMessageToChat(teamId, messageData, isSender) {
    var chatMessagesList = $('#chat-messages-list-' + teamId);

    if (chatMessagesList.length === 0) {
        console.error('Chat message list for team ID ' + teamId + ' not found.');
        return;
    }

    var timestamp = new Date();
    if (messageData.created_at) {
        timestamp = new Date(messageData.created_at);
    }
    
    var formattedDateTime = timestamp.toLocaleString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
    });

    // Use the authenticated user's avatar if the message is from the sender
    var avatarUrl = isSender 
        ? authUserAvatar 
        : (messageData.sender_avatar 
            ? `/storage/${messageData.sender_avatar}` 
            : '{{ asset("dist/img/avatar5.png") }}');

    console.log('Avatar URL:', avatarUrl);  // Debugging line to verify URL

    var messageHtml = `
        <li class="list-group-item d-flex ${isSender ? 'flex-row-reverse' : ''} align-items-center mb-2">
            <img src="${avatarUrl}" class="img-circle elevation-2" style="width: 40px; height: 40px; margin-${isSender ? 'left' : 'right'}: 10px;">
            <div class="direct-chat-msg ${isSender ? 'right' : ''}">
                <div class="direct-chat-text ${isSender ? 'bg-primary text-white' : 'bg-light'} p-3" style="border-radius: 15px;">
                    <strong>${isSender ? 'You' : messageData.sender_name}:</strong><br>
                    ${messageData.message_body}
                    <br><small class="text-muted">${formattedDateTime}</small>
                </div>
            </div>
        </li>
    `;

    chatMessagesList.append(messageHtml);
    scrollToBottom();
}



// Load team messages dynamically
$(document).on('click', '.team-link', function(e) {
    e.preventDefault(); // Prevent default link behavior
    var teamId = $(this).data('team-id');
    $.get('/teams/' + teamId + '/messages', function(data) {
        var chatContainer = $('#chat-container');
        chatContainer.html(`
            <div class="card card-primary">
                <div class="card-header">
                    <h4 class="card-title text-center">Chat for Team ${teamId}</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group chat-messages-list" id="chat-messages-list-${teamId}" style="height: 400px; overflow-y: scroll;">
                    </ul>
                    <form id="chat-form">
                        @csrf
                        <div class="input-group">
                            <input type="text" id="message-input" name="message" class="form-control" placeholder="Type a message...">
                            <span class="input-group-append">
                                <button class="btn btn-primary" type="submit">Send</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        `);

        // Attach form submission handler for the selected team
        attachFormSubmission(teamId);

        // Append existing messages
        var chatMessagesList = $('#chat-messages-list-' + teamId);
        chatMessagesList.empty();

        data.forEach(function(message) {
    appendMessageToChat(teamId, {
        message_body: message.message,
        sender_name: message.user.name,
        sender_avatar: message.user.profile_photo_path ? message.user.profile_photo_path : null  // Pass profile_photo_path directly
    }, message.user_id === {{ Auth::id() }});
});
    });
});

    });
</script>

<style>
    /* Team list styles */
    .team-list .nav-link {
        border-bottom: 1px solid #f1f1f1;
    }

    .team-list .nav-link:hover {
        background-color: #f8f9fa;
        transition: background-color 0.3s ease;
    }

    /* Message bubble styles */
    .direct-chat-text {
        border-radius: 20px;
        padding: 10px;
        max-width: 80%;
    }

    .img-circle {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Custom scrollbar for the team list */
    .team-list {
        scrollbar-width: thin; /* For Firefox */
        scrollbar-color: #007bff #f1f1f1; /* Scrollbar color for Firefox */
    }

    /* For WebKit browsers (Chrome, Safari, etc.) */
    .team-list::-webkit-scrollbar {
        width: 8px;
    }

    .team-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .team-list::-webkit-scrollbar-thumb {
        background-color: #007bff;
        border-radius: 10px;
        border: 2px solid #f1f1f1;
    }

    /* Scrollbar hover effect */
    .team-list::-webkit-scrollbar-thumb:hover {
        background-color: #0056b3;
    }

    /* Chat container adjustments */
    .chat-body {
        background-color: #f9f9f9;
        padding: 15px;
    }

    .input-group .form-control {
        border-radius: 20px;
    }

    .input-group-append .btn {
        border-radius: 20px;
    }
</style>
@endsection
