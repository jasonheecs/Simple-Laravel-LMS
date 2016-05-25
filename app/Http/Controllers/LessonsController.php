<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Lesson;

class LessonsController extends Controller
{
    /**
     * Show details for one lesson
     */
    public function show(Lesson $lesson)
    {
        $lesson->load('files');
        
        return view('lessons.show', [
            'lesson' => $lesson
        ]);
    }
}
