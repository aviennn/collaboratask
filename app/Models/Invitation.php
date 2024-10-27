<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'inviter_id', 'invitee_id', 'email', 'status'];

    // Define the relationship to the Team
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Define the relationship to the Inviter (User who sent the invite)
    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    // Define the relationship to the Invitee (User who received the invite)
    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }
}
