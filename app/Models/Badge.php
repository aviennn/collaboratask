<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Badge extends Model
{
    protected $fillable = ['name', 'description', 'icon', 'criteria'];

    // A badge can belong to many users (many-to-many relationship)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')->withTimestamps();
    }
}
