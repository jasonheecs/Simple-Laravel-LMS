<?php

use Illuminate\Database\Seeder;

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
        factory(App\User::class, 5)->create()->each(function($user) {
            $student_role = App\Role::where('name', 'student')->first();
            $user->roles()->save($student_role);

            $course = App\Course::first();

            $student = new App\Student;
            $student->user_id = $user->id;
            $student->course_id = $course->id;
            $student->save();

            $course->students()->save($student);
        });

        // lecturers
        factory(App\User::class, 2)->create()->each(function($user) {
            $lecturer_role = App\Role::where('name', 'lecturer')->first();
            $user->roles()->save($lecturer_role);

            $course = App\Course::first();

            $lecturer = new App\Lecturer;
            $lecturer->user_id = $user->id;
            $lecturer->course_id = $course->id;
            $lecturer->save();

            $course->lecturers()->save($lecturer);
        });

        $user = App\User::create(['name' => 'Jason Hee',
                                 'email' => 'jason@jason.com',
                                 'password' => bcrypt('password')]);

        $user->roles()->save(App\Role::where('name', 'admin')->first());
        $user->roles()->save(App\Role::where('name', 'lecturer')->first());
    }
}
