<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Course;
use App\Uploaders\AvatarUploader;

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
            $this->setUserToStudent($user, Course::first());
            $this->processAvatar($user);
        });

        // lecturers
        factory(User::class, 2)->create()->each(function($user) {
            $this->setUserToLecturer($user, Course::first());
            $this->processAvatar($user);
        });

        // superadmin
        $user = factory(User::class, 1)->create([
          'name' => 'Demo Admin',
          'email' => 'admin@admin.com',
          'password' => bcrypt('password'),
          'avatar' => 'http://api.adorable.io/avatars/150/Admin'
        ]);
        $user->addRole(App\Role::getAdminRole());
        $user->addRole(App\Role::getSuperAdminRole());
        $this->setUserToLecturer($user, Course::first());
        $this->processAvatar($user);

        // demo student
        $user = factory(User::class, 1)->create([
          'name' => 'Demo Student',
          'email' => 'student@student.com',
          'password' => bcrypt('password'),
          'avatar' => 'http://api.adorable.io/avatars/150/Student'
        ]);
        $this->setUserToStudent($user, Course::first());
        $this->processAvatar($user);
    }

    private function setUserToStudent(User $user, Course $course)
    {
        // $student_role = App\Role::where('name', 'user')->first();
        // $user->roles()->save($student_role);
        $user->addRole(App\Role::where('name', 'user')->first());
        
        $student = new App\Student;
        $student->user_id = $user->id;
        $student->course_id = $course->id;
        $student->save();

        $course->students()->save($student);
    }

    private function setUserToLecturer(User $user, Course $course)
    {
        // $lecturer_role = App\Role::where('name', 'user')->first();
        // $user->roles()->save($lecturer_role);
        $user->addRole(App\Role::where('name', 'user')->first());

        $lecturer = new App\Lecturer;
        $lecturer->user_id = $user->id;
        $lecturer->course_id = $course->id;
        $lecturer->save();

        $course->lecturers()->save($lecturer);
    }

    private function processAvatar(User $user)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $user->avatar);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        if ($data) {
            $imagedata = imagecreatefromstring($data);
        }

        if ($imagedata !== false) {
            $avatarUploader = new AvatarUploader($imagedata);
            $filename = 'user_' . $user->id;
            $uploadedFile = $avatarUploader->upload(
                $filename,
                public_path(config('constants.upload_dir.users')),
                150,
                150,
                true,
                true
            );

            $user->setAvatar(url(config('constants.upload_dir.users'). $uploadedFile));
        }
    }
}
