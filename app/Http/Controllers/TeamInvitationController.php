<?php
namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;

class TeamInvitationController extends Controller
{
    // Show all pending invitations for the logged-in user
    public function index()
    {
        
        $invitations = Invitation::where('invitee_id', Auth::id())
                                  ->where('status', 'pending')
                                  ->get();

        return view('teams.invitations', compact('invitations'));
    }

    // Accept a team invitation
    public function accept($invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);
    
        // Check if the invitation is pending and belongs to the authenticated user
        if ($invitation->invitee_id != Auth::id() || $invitation->status != 'pending') {
            return redirect()->route('user.teams.index')->with('error', 'Invalid invitation.');
        }
    
        // Mark the invitation as accepted
        $invitation->status = 'accepted';
        $invitation->save();
    
        // Add the user to the team
        $team = $invitation->team;
        $team->members()->attach(Auth::id());
    
        return redirect()->route('user.teams.index')->with('success', 'You have successfully joined the team.');
    }
    
    public function reject($invitationId)
    {
        $invitation = Invitation::findOrFail($invitationId);
    
        // Check if the invitation is pending and belongs to the authenticated user
        if ($invitation->invitee_id != Auth::id() || $invitation->status != 'pending') {
            return redirect()->route('user.teams.index')->with('error', 'Invalid invitation.');
        }
    
        // Mark the invitation as rejected
        $invitation->status = 'rejected';
        $invitation->save();
    
        return redirect()->route('user.teams.index')->with('success', 'You have rejected the team invitation.');
    }
}