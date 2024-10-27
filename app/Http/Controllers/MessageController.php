<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;
use App\Notifications\NewMessageNotification;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    public function index(Team $team, $messageId = null)
{
    // Check if the user is part of the team or an admin
    if (Auth::user()->usertype != 'admin' && !Auth::user()->teams->contains($team->id)) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Get messages along with user data
    $messages = $team->messages()->with('user')->get();

    // Fetch the user's teams for the sidebar
    $userTeams = Auth::user()->teams()->with('messages')->get();

    // Pass the $messageId to the view, which will be null if not provided
    return view('messages.index', compact('team', 'messages', 'messageId', 'userTeams'));
}

    


public function store(Request $request, Team $team)
{
    // Validate the request, including optional file validation
    $request->validate([
        'message' => 'required|string|max:1000',  // Max 1000 characters for the message
        'file' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf,doc,docx',  // 2MB max size, allowed file types
    ]);

    // Create a new message instance
    $message = Message::create([
        'team_id' => $team->id,
        'user_id' => Auth::id(),
        'message' => $request->message,
    ]);

    // Handle file upload if a file is attached
    if ($request->hasFile('file')) {
        // Get the original file name and sanitize it to avoid issues with special characters
        $originalFileName = $request->file('file')->getClientOriginalName();
        $sanitizedFileName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $originalFileName);  // Replace any special characters with underscores

        // Store the file in 'public/chat_files' directory with the original name
        $path = $request->file('file')->storeAs('chat_files', $sanitizedFileName, 'public');  // Save the file with the original name

        // Save the file URL and original file name in the message record
        $message->file_url = Storage::url($path);  // Generate a public URL for the file
        $message->original_file_name = $sanitizedFileName;  // Save the sanitized original file name
        $message->save();  // Save the updated message with the file URL and original name
    }

    // Broadcast the MessageSent event
    broadcast(new MessageSent($message))->toOthers();

    // Trigger notifications for other team members
    foreach ($team->members as $member) {
        if ($member->id !== Auth::id()) {
            $member->notify(new NewMessageNotification($message));
        }
    }

    // Return the newly created message with the file URL and original file name (if exists)
    return response()->json([
        'id' => $message->id,
        'message' => $message->message,
        'file_url' => $message->file_url ?? null,  // Include the file URL in the response (if exists)
        'original_file_name' => $message->original_file_name ?? null,  // Include the original file name
        'user' => [
            'id' => $message->user->id,
            'name' => $message->user->name,
        ],
        'created_at' => $message->created_at->diffForHumans(),
    ], 201);
}


    public function getMessages(Team $team)
    {
        // Check if the user is authorized to see the messages for this team
        if (!Auth::user()->teams->contains($team->id)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        // Retrieve the messages and the users who sent them
        $messages = $team->messages()->with('user')->get();
    
        // Return the messages in JSON format for the frontend
        return response()->json($messages);
    }
    
    public function allMessages()
    {
        $user = Auth::user();
    
        // Get all teams the user belongs to, along with their messages
        $userTeams = $user->teams()->with(['messages' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->get();
    
        // No specific team selected, so pass null for $team and $messageId
        return view('messages.index', [
            'userTeams' => $userTeams,
            'team' => null,
            'messages' => [],
            'messageId' => null,
        ]);
    }
    


}
