<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Border extends Model
{
    protected $fillable = ['name', 'description', 'image', 'criteria'];

    // A border can belong to many users (many-to-many relationship)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_borders')->withPivot('is_active')->withTimestamps();
    }
}
