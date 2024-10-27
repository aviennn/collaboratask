<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionDuration extends Model
{
    protected $table = 'session_durations';

    // Define the fillable fields
    protected $fillable = [
        'user_id',
        'login_time',
        'logout_time',
        'duration_in_minutes',
    ];

    // Optional: If you want to handle created_at and updated_at automatically
    public $timestamps = true;
}
