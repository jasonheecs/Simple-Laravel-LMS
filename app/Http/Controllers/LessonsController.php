<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Lesson;

class LessonsController extends Controller
{
    private $validationRules = [
        'title' => 'required',
        'body'  => 'required'
    ];

    public function create()
    {
        return view('lessons.create');
    }

    public function store(Request $request, Course $course)
    {
        $this->validate($request, $this->validationRules);

        $lesson = new Lesson;
        $lesson->title = $request->title;
        $lesson->body = $request->body;

        $course->addLesson($lesson);

        return back();
    }

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
        $this->validate($request, $this->validationRules);

        $lesson->title = $request->title;
        $lesson->body = $request->body;
        $lesson->save();

        if ($request->ajax()) {
            return response()->json(['response' => 'Lesson Updated']); 
        }

        return back();
    }

    public function delete(Request $request, Lesson $lesson)
    {
        $lesson->delete();

        return redirect()->route('course', [$lesson->course]);
    }

    /**
     * Upload images inserted via Medium WYSIWYG editor
     */
    public function upload(Request $request)
    {
        $file = $request->file('files')[0];

        if ($file->isValid()) {
            $fileName = time().'-'.$file->getClientOriginalName();
            $destination = public_path() . '/uploads/lessons/';
            $file->move($destination, $fileName);

            $response = ['files' => [['url' => url('/uploads/lessons/'. $fileName)]]];
        } else {
            echo 'Image Upload Error!';
            $response = ['files' => [['url' => url('/uploads/lessons/error.png')]]];
        }

        return json_encode($response);
    }
}
