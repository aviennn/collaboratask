<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['widget_id', 'content'];

    // Define relationship: A note belongs to a widget
    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }
}
