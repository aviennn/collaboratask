<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetChecklist extends Model
{
    use HasFactory;

    protected $fillable = ['widget_id', 'content', 'is_checked'];

    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }
}
