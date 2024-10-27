<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;
use App\Models\Title;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
// Handle border selection
public function updateBorder(Request $request)
{
    $user = Auth::user();

    // Handle sample borders
    if (in_array($request->border_id, ['sample-1', 'sample-2'])) {
        // Store sample border in 'selected_border' column
        $user->selected_border = $request->border_id;
    } else {
        // Validate the border selection from the database
        $validatedData = $request->validate([
            'border_id' => 'required|exists:borders,id',
        ]);

        // Deactivate all borders
        $user->borders()->updateExistingPivot($user->borders->pluck('id')->toArray(), ['is_active' => false]);

        // Activate the selected border
        $user->borders()->updateExistingPivot($validatedData['border_id'], ['is_active' => true]);

        // Clear any previously selected sample borders
        $user->selected_border = null;
    }

    // Save and redirect with success message
    $user->save();
    return redirect()->back()->with('status', 'Profile border updated successfully.');
}




public function index(Request $request)
{
    $user = Auth::user();
    $titles = Title::all(); // Fetch all available titles

    // Paginate activities (10 per page)
    $activities = Activity::where('causer_id', $user->id)
    ->latest()
    ->get();

    // Determine the correct border image
    if ($user->selected_border === 'sample-1') {
        $borderImage = asset('images/sample-borders/border-sample-1.png');
    } elseif ($user->selected_border === 'sample-2') {
        $borderImage = asset('images/sample-borders/border-sample-2.png');
    } else {
        // Check if the user has an active border from the database
        $activeBorder = $user->borders()->wherePivot('is_active', 1)->first();
        $borderImage = $activeBorder ? asset($activeBorder->image) : asset('images/placeholder/border-placeholder.png');
    }

    // Handle AJAX requests
    if ($request->ajax()) {
        return response()->json([
            'activities' => view('profile.partials.activities', compact('activities'))->render(),
            'pagination' => $activities->links()->render(),
        ]);
    }

    // Pass the user, border image, activities, and titles to the view
    return view('profile.index', [
        'user' => $user,
        'borderImage' => $borderImage,
        'activities' => $activities,
        'titles' => $titles, // Pass the titles to the view
    ]);
}
public function selectTitle(Request $request)
{
    $request->validate([
        'title_id' => 'required|exists:titles,id',
    ]);

    $user = Auth::user();
    $user->selected_title_id = $request->input('title_id');
    $user->save();

    return redirect()->route('profile.index')->with('status', 'Title updated successfully!');
}

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'full_name' => 'nullable|string',
            'email' => 'required|string|email|max:255',
            'bio' => 'nullable|string',
            'about_me' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'skills' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $changes = [];

        if ($user->name !== $request->name) {
            $changes[] = "Name changed from {$user->name} to {$request->name}";
            $user->name = $request->name;
        }

        if ($user->email !== $request->email) {
            $changes[] = "Email changed from {$user->email} to {$request->email}";
            $user->email = $request->email;
        }

        if ($user->bio !== $request->bio) {
            $changes[] = "Bio updated";
            $user->bio = $request->bio;
        }

        if ($user->about_me !== $request->about_me) {
            $changes[] = "About Me section updated";
            $user->about_me = $request->about_me;
        }

        if ($user->address !== $request->address) {
            $changes[] = "Address updated";
            $user->address = $request->address;
        }

        if ($user->education !== $request->education) {
            $changes[] = "Education updated";
            $user->education = $request->education;
        }

        if ($request->has('skills')) {
            $skillsArray = array_map('trim', explode(',', $request->skills));
            $encodedSkills = json_encode($skillsArray);

            if ($user->skills !== $encodedSkills) {  // Compare the old and new skills JSON
                $user->skills = $encodedSkills;
                $changes[] = "Skills updated";
            }
        }

        if ($request->hasFile('profile_photo')) {
            $originalName = $request->file('profile_photo')->getClientOriginalName();
            $uniqueName = uniqid() . '_' . $originalName;
        
            // Debug: Log the original name and unique name
            \Log::info('Original File Name: ' . $originalName);
            \Log::info('Unique File Name: ' . $uniqueName);
        
           // Store the file in 'public/profile_photos'
$path = $request->file('profile_photo')->storeAs('profile_photos', $uniqueName, 'public');

        
            // Debug: Log the path where the file is saved
            \Log::info('File stored at: ' . $path);
        
            // Update the user profile photo path
            $user->profile_photo_path = $path;
            $changes[] = "Profile picture updated";
        }

        $user->save();

        // Log changes if there are any
        if (!empty($changes)) {
            activity()
                ->causedBy($user)
                ->performedOn($user)
                ->withProperties(['changes' => $changes])
                ->log('Updated profile: ' . implode(', ', $changes));
        }

        return redirect()->route('profile.index')->with('status', 'Profile updated!');
    }



    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
