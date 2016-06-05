<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Course;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // students
        factory(User::class, 5)->create()->each(function($user) {
            $this->setUserToStudent($user, App\Course::first());
        });

        // lecturers
        factory(User::class, 2)->create()->each(function($user) {
            $this->setUserToLecturer($user, Course::first());
        });

        $user = User::create(['name' => 'Jason Hee',
                                 'email' => 'jason@jason.com',
                                 'password' => bcrypt('password')]);
        $user->roles()->save(App\Role::where('name', 'superadmin')->first());
        $this->setUserToLecturer($user, Course::first());

        $user = User::create(['name' => 'Demo Student',
                                 'email' => 'student@student.com',
                                 'password' => bcrypt('password')]);
        $this->setUserToStudent($user, Course::first());
    }

    private function setUserToStudent(User $user, Course $course)
    {
        $student_role = App\Role::where('name', 'user')->first();
        $user->roles()->save($student_role);
        
        $student = new App\Student;
        $student->user_id = $user->id;
        $student->course_id = $course->id;
        $student->save();

        $course->students()->save($student);
    }

    private function setUserToLecturer(User $user, Course $course)
    {
        $lecturer_role = App\Role::where('name', 'user')->first();
        $user->roles()->save($lecturer_role);

        $lecturer = new App\Lecturer;
        $lecturer->user_id = $user->id;
        $lecturer->course_id = $course->id;
        $lecturer->save();

        $course->lecturers()->save($lecturer);
    }
}
