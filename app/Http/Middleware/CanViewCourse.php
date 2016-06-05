<?php

namespace App\Http\Middleware;

use Closure;
use App\Course;

/**
 * This middleware checks if the user can view a course
 * (i.e: the user is an admin / is a lecturer for the course / is a student of the course)
 */
class CanViewCourse
{
    protected $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->course) {
            $course = $request->course;
        } elseif ($request->lesson) {
            $course = $request->lesson->course;
        } elseif ($request->course_id) {
            $course = $this->course->find($request->course_id);
        }

        if (!$request->user()->isStudentIn($course) && !$request->user()->canEdit($course)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['response' => 'Unauthorized access'], 401);
            }

            flash('You do not have permission to access this page', 'danger');
            return back();
        }

        return $next($request);
    }
}
