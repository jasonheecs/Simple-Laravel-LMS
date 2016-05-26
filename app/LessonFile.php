<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LessonFile extends Model
{
    protected $fillable = [
        'name', 'description', 'url',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
