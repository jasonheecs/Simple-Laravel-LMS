<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Lesson;
use App\LessonFile;

class LessonFilesController extends Controller
{
    /**
     * Adds a new LessonFile to the Lesson
     * @param  Request $request
     * @param  Lesson  $lesson
     */
    public function store(Request $request, Lesson $lesson)
    {
        $this->validate($request, [
            'filename' => 'required',
            'url'      => 'required'
        ]);

        $file = new LessonFile;
        $file->name = $request->filename;
        $file->url = $request->url;

        if ($request->has('description')) {
            $file->description = $request->description;
        }

        $lesson->addFile($file);

        return back();
    }

    public function edit(LessonFile $file)
    {
        return view('lessonfiles.edit', [
            'file' => $file
        ]);
    }

    public function update(Request $request, LessonFile $file)
    {
        $this->validate($request, [
            'filename' => 'required',
            'url'      => 'required'
        ]);
        
        if ($request->has('description')) {
            $file->update([
              'name' => $request->filename,
              'url' => $request->url,
              'description' => $request->description
            ]);
        } else {
            $file->update([
              'name' => $request->filename,
              'url' => $request->url
            ]);
        }

        return redirect()->route('lesson', [$file->lesson]);
    }

    public function destroy(Request $request, LessonFile $file)
    {
        $file->delete();

        return back();
    }
}
