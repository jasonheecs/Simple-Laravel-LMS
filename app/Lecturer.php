<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_lecturer');
    }
}
