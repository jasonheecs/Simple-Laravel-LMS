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
    public function isInstructorIn()
    {
        $lecturers = Lecturer::where('user_id', $this->id)->get();
        return $lecturers->load('courses');
    }

    /**
     * Get a Collection of Student models which corresponds to this user
     * @return Collection [Collection of Students]
     */
    public function isStudentIn()
    {
        $students = Student::where('user_id', $this->id)->get();
        return $students->load('courses');
    }
}
