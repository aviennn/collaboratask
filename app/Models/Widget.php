<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $fillable = ['user_id', 'type', 'content'];

    // Define relationship: A widget has many notes
    public function notes()
    {
        return $this->hasMany(Note::class, 'widget_id');
    }

    public function checklists()
{
    return $this->hasMany(WidgetChecklist::class);
}

}
