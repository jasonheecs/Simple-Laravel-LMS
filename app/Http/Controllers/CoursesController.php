<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Course;
use App\Uploaders\ImageUploader;
use App\Uploaders\CourseImageUploader;
use Gate;

class CoursesController extends Controller
{
    /**
     * Show all courses
     */
    public function index()
    {
        $courses = Course::all();
        $courses = $courses->each(function ($course) {
            if ($course->image) {
                $course->image = generateThumbnailImagePath($course->image);
            }
        });

        return view('courses.index', [
            'courses' => $courses
        ]);
    }

    /**
     * Show view for creating new course
     */
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
        if (Gate::denies('store', Course::class)) {
            return parent::unauthorizedResponse(redirect()->action('CoursesController@index'), $request);
        }

        if ($request->has('save')) {
            return $this->createNewCourse($request);
        } 
        elseif ($request->has('cancel')) {
            return $this->cancelCreateNewCourse($request);
        }
    }

    public function update(Request $request, Course $course)
    {
        if (Gate::denies('update', $course)) {
            return parent::unauthorizedResponse(redirect()->back(), $request);
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
        if (Gate::denies('update', $course)) {
            return parent::unauthorizedResponse(redirect()->back(), $request);
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
        if (Gate::denies('update', $course)) {
            return parent::unauthorizedResponse(redirect()->back(), $request);
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
        if (Gate::denies('destroy', $course)) {
            return parent::unauthorizedResponse(redirect()->back(), $request);
        }

        $course->delete();

        flash('Course deleted', 'success');

        return redirect()->action('CoursesController@index');
    }

    private function createNewCourse(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $course = new Course;
        $course->title = $request->title;
        $course->save(); // save course here so that we can get an id

        if ($request->has('image')) {
            $filename = 'course_' . $course->id;
            $tmpImgFilePath = public_path(config('constants.upload_dir.tmp')) . getSubstrAfterLastSlash($request->image);

            if (\File::exists($tmpImgFilePath)) {
                $courseImgUploader = new CourseImageUploader($tmpImgFilePath);

                $uploadedFile = $courseImgUploader->upload(
                    $filename,
                    public_path(config('constants.upload_dir.courses')),
                    1500,
                    550
                );

                $course->setImage(url(config('constants.upload_dir.courses'). $uploadedFile));
                \File::delete($tmpImgFilePath);
            }
        }

        flash('Course added', 'success');

        return redirect()->route('courses.index');
    }

    private function cancelCreateNewCourse(Request $request)
    {
        // delete any temporary uploaded course image file
        if ($request->has('image')) {
            \File::delete(public_path(config('constants.upload_dir.tmp')) . basename($request->image));
        }

        return redirect()->route('courses.index');
    }

    /**
     * Handles uploading of course banner image file.
     * If $course_id is 0, means that the course model is a temporary one
     * (most likely one made during the create() view before saving the model)
     * If course model is temporary, upload the image file to a temporary directory first.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $course_id
     * @return JSON   JSON response
     */
    public function upload(Request $request, $course_id)
    {
        $tmp_course_id = 0;

        if ($course_id == $tmp_course_id) { // temporary course model
            if (Gate::denies('update', Course::class)) {
                return parent::unauthorizedResponse(redirect()->back(), $request);
            }

            $imageUploader = new ImageUploader($request->file('files')[0]);
            $response = $this->uploadToTmp($imageUploader);
        } else {
            if (Gate::denies('update', Course::find($course_id))) {
                return parent::unauthorizedResponse(redirect()->back(), $request);
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
        }

        return json_encode($response);
    }

    /**
     * Upload avatar image file to the Courses tmp directory
     * Used as an ajax endpoint for the jQuery file upload plugin
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  \App\Uploaders\ImageUploader $imageUploader
     * @return array          Response Array containing the directory path of the uploaded file
     */
    private function uploadToTmp($imageUploader)
    {
        $filename = time().'-'.'course_' . generate_random_str(20);
        $uploadedFile = $imageUploader->upload(
            $filename,
            public_path(config('constants.upload_dir.tmp')),
            1500,
            550
        );

        if ($uploadedFile) {
            $tmpCourseImg = $uploadedFile;
            $response = ImageUploader::formatResponse(url(config('constants.upload_dir.tmp') . $tmpCourseImg));
        } else {
            echo 'Image Upload Error!';
            $response = ImageUploader::getErrorResponse();
        }

        return $response;
    }
}
