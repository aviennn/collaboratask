<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Add this line

class Team extends Model
{
    use HasFactory, SoftDeletes; // Include SoftDeletes trait

    protected $fillable = [
        'name',
        'description',
        'creator_id',
        'has_rewards',
        'image',
    ];

    // Define the date fields to include 'deleted_at'
    protected $dates = ['deleted_at'];

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function rewards()
    {
        return $this->hasMany(Reward::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function completionPercentage()
    {
        $totalTasks = $this->tasks()->count();
        $completedTasks = $this->tasks()->where('status', 'done')->count();
        return $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
    }

    // Soft delete related models when a team is soft deleted
    protected static function booted()
    {
        static::deleting(function ($team) {
            if ($team->isForceDeleting()) {
                // Hard delete related models if the team is force deleted
                $team->tasks()->forceDelete();
                $team->rewards()->forceDelete();
                $team->messages()->forceDelete();
            } else {
                // Soft delete related models when the team is soft deleted
                $team->tasks()->delete();
                $team->rewards()->delete();
                $team->messages()->delete();
            }
        });

        static::restoring(function ($team) {
            // Restore related models when the team is restored
            $team->tasks()->withTrashed()->restore();
            $team->rewards()->withTrashed()->restore();
            $team->messages()->withTrashed()->restore();
        });
    }
}
