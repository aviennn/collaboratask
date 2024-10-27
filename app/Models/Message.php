<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id', 
        'user_id', 
        'message'
    ];

    protected $dates = ['deleted_at'];
    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
