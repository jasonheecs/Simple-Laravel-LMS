<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'course_student');
    }

    public function lecturers()
    {
        return $this->belongsToMany(Lecturer::class, 'course_lecturer');
    }
}
