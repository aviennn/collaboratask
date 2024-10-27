<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Store a new note for a specific widget.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $widgetId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $widgetId)
    {
        // Validate the input
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
    
        // Create a new note and associate it with the widget
        $note = Note::create([
            'widget_id' => $widgetId,
            'content' => $request->input('content'),
        ]);
    
        // Return the note content and ID as JSON response
        return response()->json([
            'status' => 'success',
            'note' => [
                'id' => $note->id,         // Include the note's ID
                'content' => $note->content
            ]
        ]);
    }
    

    /**
     * Delete a note.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the note by ID
        $note = Note::findOrFail($id);

        // Delete the note
        $note->delete();

        // Return success response
        return response()->json(['status' => 'success']);
    }
}
