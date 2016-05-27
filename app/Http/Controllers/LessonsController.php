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
        $this->validate($request, [
            'title' => 'required',
            'body'  => 'required'
        ]);

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
