<?php

namespace App\Http\Controllers;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TitleController extends Controller
{
    // Display all available titles
    public function index()
    {
        $titles = Title::all();
        $user = Auth::user();
        return view('profile.select-title', compact('titles', 'user'));
    }

    // Handle title selection
    public function select(Request $request)
    {
        $request->validate([
            'title_id' => 'required|exists:titles,id',
        ]);

        $user = Auth::user();
        $user->selected_title_id = $request->input('title_id');
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Title updated successfully!');
    }
}