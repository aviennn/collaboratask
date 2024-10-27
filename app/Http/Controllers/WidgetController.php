<?php

namespace App\Http\Controllers;

use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Note;

use App\Models\WidgetChecklist;


class WidgetController extends Controller
{
    // Store a new widget in the database
    public function store(Request $request)
{
    // Validate the incoming data to ensure 'type' is provided
    $validated = $request->validate([
        'type' => 'required|string|in:checklist,note',
        'content' => 'nullable|string', // Allow content to be optional
    ]);

    // Create a new widget with the provided type
    $widget = Widget::create([
        'user_id' => Auth::id(),
        'type' => $validated['type'], // Use the validated type from the request
        'content' => $validated['content'] ?? '', // Default to empty if content is not provided
    ]);

    // Return the widget ID and type as a JSON response
    return response()->json([
        'id' => $widget->id,
        'type' => $widget->type,
        'content' => $widget->content,
    ]);
}


    public function storeNote(Request $request, $widgetId)
    {
        // Validate the note content
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
    
        // Create a new note and associate it with the widget
        $note = Note::create([
            'widget_id' => $widgetId,
            'content' => $request->input('content'),
        ]);
    
        // Return just the note content in the response
        return response()->json([
            'status' => 'success',
            'note' => $note->content,  // Return only the content of the note
        ]);
    }
    



    // Fetch all widgets for the authenticated user
    public function index()
{
    // Get all widgets for the current user, including their associated checklist items
    $widgets = Widget::where('user_id', Auth::id())
                     ->with('checklists') // Eager load the 'checklists' relationship
                     ->get();

    // Return the dashboard view with the widgets
    return view('dashboard', ['widgets' => $widgets]);
}

    // Update an existing widget's content
    public function update(Request $request, $id)
{
    // Retrieve the widget by its ID
    $widget = Widget::findOrFail($id);

    // Ensure the widget belongs to the authenticated user
    if ($widget->user_id != Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    // Update the widget's content
    $content = $request->input('content');

    // Ensure content is not null before updating
    if (!is_null($content)) {
        $widget->content = $content;
        $widget->save(); // Save to the database
    } else {
        return response()->json(['error' => 'Content is empty'], 400);
    }

    return response()->json(['status' => 'success']);
}


    // Delete a widget
    public function destroy($id)
    {
        $widget = Widget::findOrFail($id);

        // Check if the widget belongs to the authenticated user
        if ($widget->user_id != Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the widget
        $widget->delete();

        return response()->json(['status' => 'success']);
    }

    // Store a new checklist item
    public function storeChecklistItem(Request $request)
    {
        $validated = $request->validate([
            'widget_id' => 'required|exists:widgets,id',
            'content' => 'required|string|max:255',
        ]);
    
        // Create the checklist item
        $item = WidgetChecklist::create([
            'widget_id' => $validated['widget_id'],
            'content' => $validated['content'],
            'is_checked' => false,
        ]);
    
        // Ensure that the response includes 'id' and 'content'
        return response()->json([
            'success' => true,
            'id' => $item->id,       // Make sure this is included
            'content' => $item->content  // Make sure this is included
        ]);
    }
    
// Update checklist item (e.g., mark as checked/unchecked)
public function updateChecklistItem(Request $request, $id)
{
    $validated = $request->validate([
        'is_checked' => 'required|boolean', // Validation for checkbox status
    ]);

    $checklistItem = WidgetChecklist::findOrFail($id);
    $checklistItem->update(['is_checked' => $validated['is_checked']]);

    return response()->json(['success' => true]);
}

// Delete a checklist item
public function deleteChecklistItem($id)
{
    $checklistItem = WidgetChecklist::findOrFail($id);
    $checklistItem->delete();

    return response()->json(['success' => true]);
}
}
