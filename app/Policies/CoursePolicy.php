<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Course;
use App\User;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given course can be seen by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Course  $course
     * @return boolean
     */
    public function show(User $user, Course $course)
    {
        return $user->is('admin') || $user->isLecturerIn($course) || $user->isStudentIn($course);
    }

    /**
     * Determine if the current user can create a new course.
     * @param  \App\User  $user
     * @return boolean
     */
    public function createAny(User $user)
    {
        return $user->is('admin');
    }

    /**
     * Determine if the given course can be updated by the user.
     *
     * @param  \App\User  $user
     * @param  \App\Course  $course
     * @return boolean
     */
    public function update(User $user, Course $course)
    {
        return $user->is('admin') || $user->isLecturerIn($course);
    }

    /**
     * Determine if the given course can be deleted by the user
     *
     * @param  \App\User  $user
     * @param  \App\Course  $course
     * @return boolean
     */
    public function destroy(User $user, Course $course)
    {
        return $user->is('admin') || $user->isLecturerIn($course);
    }

    /**
     * Intercept all checks for superadmin user
     * @param  \App\User $user    [description]
     * @return  boolean
     */
    public function before(User $user, $ability)
    {
        if ($user->is('superadmin')) {
            return true;
        }
    }
}
