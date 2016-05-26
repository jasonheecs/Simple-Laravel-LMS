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
        $lesson->load('course');
        $lesson->load('files');
        
        return view('lessons.show', [
            'lesson' => $lesson
        ]);
    }

    public function update(Request $request, Lesson $lesson)
    {
        // $this->validate($request, [
        //     'filename' => 'required',
        //     'url'      => 'required'
        // ]);

        return $request;
    }

    public function delete(Request $request, Lesson $lesson)
    {
        $lesson->delete();

        return redirect()->route('course', [$lesson->course]);
    }
}
