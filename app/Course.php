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

    public function addLesson(Lesson $lesson)
    {
        return $this->lessons()->save($lesson);
    }

    public function getLecturers()
    {
        $lecturers = [];
        foreach ($this->lecturers as $lecturer) {
            $user = User::find($lecturer->user_id);
            $lecturers[] = $user;
        }

        return $lecturers;
    }

    public function addLecturer($user_id)
    {
        $lecturer = new Lecturer();
    }
}
