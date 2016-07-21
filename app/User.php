<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    // check user role
    public function is($roleName)
    {
        foreach ($this->roles()->get() as $role) {
            if ($role->name == $roleName) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get a Collection of Instructor models which corresponds to this user
     * @return Collection [Collection of Instructors]
     */
    public function getAllInstructors()
    {
        $lecturers = Lecturer::where('user_id', $this->id)->get();
        return $lecturers->load('courses');
    }

    /**
     * Get a Collection of Student models which corresponds to this user
     * @return Collection [Collection of Students]
     */
    public function getAllStudents()
    {
        $students = Student::where('user_id', $this->id)->get();
        return $students->load('courses');
    }

    /**
     * Check if user is lecturer in a course
     * @param  Course  $course
     */
    public function isLecturerIn(Course $course)
    {
        foreach ($course->lecturers()->get() as $lecturer) {
            if ($this->id == $lecturer->user_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user is student in a course
     * @param  Course  $course
     */
    public function isStudentIn(Course $course)
    {
        foreach ($course->students()->get() as $student) {
            if ($this->id == $student->user_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user can edit a course
     * @param  Course $course
     */
    public function canEdit(Course $course)
    {
        return $this->is('superadmin') || $this->is('admin') || $this->isLecturerIn($course);
    }

    public function addRole(Role $role)
    {
        $this->roles()->attach($role->id);
    }

    public function removeRole(Role $role)
    {
        $this->roles()->detach($role->id);
    }

    public function toggleRole(Role $role)
    {
        if ($this->is($role->name)) {
            $this->removeRole($role);
        } else {
            $this->addRole($role);
        }
    }

    public function setAvatar($avatarFile)
    {
        $this->avatar = $avatarFile;
        $this->save();
    }

    public function deleteAvatar()
    {
        if ($this->avatar) {
            $avatarFile = public_path(config('constants.upload_dir.users')) . getSubstrAfterLastSlash($this->avatar);
            $avatarThumb = generateThumbnailImagePath($avatarFile);

            if (\File::exists($avatarFile)) {
                \File::delete($avatarFile);
            }

            if (\File::exists($avatarThumb)) {
                \File::delete($avatarThumb);
            }
        }
    }
}
