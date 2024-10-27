<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'font_family',
        'selected_border',  // Add this to allow for mass assignment
        'xp',      // Add xp to fillable
        'level',   // Add level to fillable
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function feedbacks()
{
    return $this->hasMany(Feedback::class);
}


public function isAdmin()
{
    
    // which stores 'admin' for admin users.
    return $this->usertype === 'admin';

  
}
    /**
     * User's relationship with tasks.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * User's relationship with teams.
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members');
    }

    /**
     * Many-to-Many relationship with badges.
     * A user can earn multiple badges.
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withTimestamps();
    }

    /**
     * Many-to-Many relationship with borders.
     * A user can unlock multiple borders.
     * The 'is_active' pivot column indicates the active border.
     */
    public function borders()
    {
        return $this->belongsToMany(Border::class, 'user_borders')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    /**
     * Get the user's active border, if any.
     */
    public function activeBorder()
    {
        return $this->borders()->wherePivot('is_active', true)->first();
    }

    public function xpUntilNextLevel()
{
    $xpThreshold = 100;  // The XP required for each level

    // Calculate how much more XP is needed to reach the next level
    $remainingXP = $xpThreshold - $this->xp;

    return $remainingXP;
}

public function calculateTotalPoints()
{
    return max(0, $this->tasks()
                ->where('status', 'done')
                ->sum('points'));  // Ensure that 0 is the minimum value returned
}

public function rewards()
    {
        return $this->belongsToMany(Reward::class, 'reward_redemptions')
                    ->withPivot('redeemed_at')
                    ->withTimestamps();
    }

    public function personalTasks()
    {
        return $this->tasks()->whereNull('team_id');
    }

    public function teamTasks()
{
    $teamIds = $this->teams()->pluck('teams.id'); // Pluck team IDs
    return Task::whereIn('team_id', $teamIds); // Get tasks based on team IDs
}

public function selectedTitle()
{
    return $this->belongsTo(Title::class, 'selected_title_id');
}

}
