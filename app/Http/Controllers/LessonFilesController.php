<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class LessonFilesController extends Controller
{
    /**
     * Adds a new LessonFile to the Lesson
     * @param  Request $request
     * @param  Lesson  $lesson
     */
    public function store(Request $request, Lesson $lesson)
    {
        return 'test';
    }
}
