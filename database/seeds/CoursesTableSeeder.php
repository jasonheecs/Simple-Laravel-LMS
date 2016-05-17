<?php

use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Course::class, 10)->create()->each(function($course) {
            $course->lessons()->saveMany(factory(App\Lesson::class, 10)->make());
        });

        // Add dummy file to one lesson
        $lesson = App\Lesson::first();
        $files = factory(App\LessonFile::class, 2)->create();
        $lesson->files()->saveMany($files);
    }
}
