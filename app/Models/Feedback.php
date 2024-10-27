<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks'; 
    // Specify which fields are mass assignable
    protected $fillable = ['user_id', 'category', 'comment', 'rating'];

   

    // Feedback belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
