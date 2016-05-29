<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function files()
    {
        return $this->hasMany(LessonFile::class);
    }

    public function addFile(LessonFile $file)
    {
        return $this->files()->save($file);
    }

    public function publish()
    {
        $this->published = true;
        $this->save();
    }

    public function unpublish()
    {
        $this->published = false;
        $this->save();
    }
}
