<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Course;
use Gate;

class CoursesController extends Controller
{
    /**
     * Show all courses
     */
    public function index()
    {
        return view('courses.index', [
            'courses' => Course::all()
        ]);
    }

    public function create()
    {
        if (Gate::denies('store', Course::class)) {
            return parent::unauthorizedResponse(redirect()->action('CoursesController@index'));
        }

        return view('courses.create');
    }

    /**
     * Show course details and lessons
     * @param  Course $course
     */
    public function show(Course $course)
    {
        if (Gate::denies('show', $course)) {
            return parent::unauthorizedResponse(redirect()->action('CoursesController@index'));
        }

        $course->load('lessons');

        return view('courses.show', [
            'course' => $course,
            'users' => \App\User::all()
        ]);
    }

    /**
     * Create a new course
     * @param  Request $request
     */
    public function store(Request $request)
    {
        if (Gate::denies('store', Course::class, $request)) {
            return parent::unauthorizedResponse(redirect()->action('CoursesController@index'));
        }

        $this->validate($request, [
            'title' => 'required'
        ]);

        $course = new Course;
        $course->title = $request->title;
        $course->save();

        flash('Course added', 'success');

        return redirect()->route('courses.index');
    }

    public function update(Request $request, Course $course)
    {
        if (Gate::denies('update', $course, $request)) {
            return parent::unauthorizedResponse(redirect()->back());
        }

        $this->validate($request, [
            'title' => 'required'
        ]);

        $course->title = $request->title;
        $course->save();

        if ($request->ajax()) {
            return response()->json(['response' => 'Course Updated']);
        }

        return back();
    }

    /**
     * Update list of lecturers for a course
     * @param  Request $request
     * @param  Course  $course
     */
    public function updateLecturers(Request $request, Course $course)
    {
        if (Gate::denies('update', $course, $request)) {
            return parent::unauthorizedResponse(redirect()->back());
        }

        if ($request->ajax()) {
            $existingLecturers = $course->getLecturers();

            // remove unchecked lecturers
            foreach ($existingLecturers as $lecturer) {
                if (!array_key_exists($lecturer->id, $request->all())) {
                    $course->removeLecturer($lecturer->id);
                }
            }

            // add checked lecturers
            foreach ($request->all() as $user_id => $checked) {
                $user = \App\User::find($user_id);
                if (!$user->isLecturerIn($course)) {
                    $course->addLecturer($user_id);
                }
            }

            return response()->json(['response' => 'Lecturers Updated']);
        }

        return back();
    }

    /**
     * Update list of students for a course
     * @param  Request $request
     * @param  Course  $course
     */
    public function updateStudents(Request $request, Course $course)
    {
        if (Gate::denies('update', $course, $request)) {
            return parent::unauthorizedResponse(redirect()->back());
        }

        if ($request->ajax()) {
            $existingStudents = $course->getStudents();

            // remove unchecked students
            foreach ($existingStudents as $student) {
                if (!array_key_exists($student->id, $request->all())) {
                    $course->removeStudent($student->id);
                }
            }

            // add checked students
            foreach ($request->all() as $user_id => $checked) {
                $user = \App\User::find($user_id);
                if (!$user->isStudentIn($course)) {
                    $course->addStudent($user_id);
                }
            }

            return response()->json(['response' => 'Students Updated']);
        }

        return back();
    }

    public function destroy(Request $request, Course $course)
    {
        if (Gate::denies('destroy', $course, $request)) {
            return parent::unauthorizedResponse(redirect()->back());
        }

        $course->delete();

        flash('Course deleted', 'success');

        return redirect()->action('CoursesController@index');
    }

    public function upload(Request $request, $course_id)
    {
        if (Gate::denies('update', Course::find($course_id), $request)) {
            return parent::unauthorizedResponse(redirect()->back());
        }
        
        $file = $request->file('files')[0];

        if ($file->isValid()) {
            $fileName = 'course_' . $course_id . '.' . $file->guessExtension();
            $destination = public_path() . '/uploads/courses/';
            $file->move($destination, $fileName);

            $course = Course::find($course_id);
            $course->image = url('/uploads/courses/'. $fileName);
            $course->save();

            $response = ['files' => [['url' => url('/uploads/courses/'. $fileName)]]];
        } else {
            echo 'Image Upload Error!';
            $response = ['files' => [['url' => url('/uploads/error.png')]]];
        }

        return json_encode($response);
    }
}
