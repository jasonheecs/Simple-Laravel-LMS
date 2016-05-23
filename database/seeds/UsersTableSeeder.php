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
        factory(App\User::class, 5)->create()->each(function($user) {
            $student_role = App\Role::where('name', 'student')->first();
            $user->roles()->save($student_role);
            $course = App\Course::first();
            $course->users()->save($user);
        });

        $user = App\User::create(['name' => 'Jason Hee',
                                 'email' => 'jason@jason.com',
                                 'password' => bcrypt('password')]);

        $user->roles()->save(App\Role::where('name', 'admin')->first());
        $user->roles()->save(App\Role::where('name', 'lecturer')->first());
    }
}
