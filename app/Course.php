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

    public function setImage($imgFile)
    {
        $this->image = $imgFile;
        $this->save();
    }

    public function getLecturers()
    {
        return $this->getUsersCollection($this->lecturers);
    }

    public function addLecturer($user_id)
    {
        $lecturer = new Lecturer();
        $lecturer->user_id = $user_id;
        $lecturer->course_id = $this->id;
        $lecturer->save();
        $lecturer->courses()->save($this);
    }

    public function removeLecturer($user_id)
    {
        $lecturer = Lecturer::where(['user_id' => $user_id, 'course_id' => $this->id])->first();
        $lecturer->delete();
    }

    public function getStudents()
    {
        return $this->getUsersCollection($this->students);
    }

    public function addStudent($user_id)
    {
        $student = new Student();
        $student->user_id = $user_id;
        $student->course_id = $this->id;
        $student->save();
        $student->courses()->save($this);
    }

    public function removeStudent($user_id)
    {
        $student = Student::where(['user_id' => $user_id, 'course_id' => $this->id])->first();
        $student->delete();
    }

    /**
     * Helper function to get a collection of User models (Lecturers/Students)
     * @param  [Model] $collection - Collection of models with user_id attribute
     * @return [Array] array of User models
     */
    private function getUsersCollection($collection) {
        $users_collection = [];
        foreach ($collection as $item) {
            $user = User::find($item->user_id);
            $users_collection[] = $user;
        }

        return $users_collection;
    }
}
