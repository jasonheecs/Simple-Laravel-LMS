<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Lesson;
use App\Course;

class LessonsController extends Controller
{
    private $validationRules = [
        'title' => 'required',
        'body'  => 'required'
    ];

    public function __construct()
    {
        $this->middleware('view.course');
        $this->middleware('edit.course', ['except' => 'show']);
    }

    public function create(Request $request)
    {
        if (!$request->course_id) {
            flash('Course id not found. Please try again.');
            return back();
        }

        return view('lessons.create', [
            'course_id' => $request->course_id
        ]);
    }

    public function store(Request $request)
    {
        if ($request->save) {
            $this->validate($request, $this->validationRules);

            $lesson = new Lesson;
            $lesson->title = $request->title;
            $lesson->body = $request->body;

            $course = Course::findOrFail($request->course_id);
            $course->addLesson($lesson);

            flash('Lesson added', 'success');
        }

        return redirect()->route('course', [$request->course_id]);
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

    public function destroy(Request $request, Lesson $lesson)
    {
        $lesson->delete();

        flash('Lesson deleted', 'success');

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
            $response = ['files' => [['url' => url('/uploads/error.png')]]];
        }

        return json_encode($response);
    }

    public function setPublishedStatus(Request $request, Lesson $lesson)
    {
        $status = '';

        if ($lesson->published) {
            $lesson->unpublish();
            $status = 'Unpublished';
        } elseif (!$lesson->published) {
            $lesson->publish();
            $status = 'Published';
        }

        if ($request->ajax()) {
            return response()->json(['response' => 'Lesson ' . $status]);
        }

        return back();
    }
}
