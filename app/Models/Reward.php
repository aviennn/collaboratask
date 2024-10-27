<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Reward extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'name',
        'description',
        'points_required',
    ];
    protected $dates = ['deleted_at'];
    
    public function team()
    {
        return $this->belongsTo(Team::class);
    }   
    public function users()
    {
        return $this->belongsToMany(User::class, 'reward_redemptions')
                    ->withPivot('redeemed_at')
                    ->withTimestamps();
    }
}
