<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseLecturerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_lecturer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->unsigned()->index();
            $table ->foreign('course_id')
                   ->references('id')->on('courses')
                   ->onDelete('cascade');
            $table->integer('lecturer_id')->unsigned()->index();
            $table ->foreign('lecturer_id')
                   ->references('id')->on('lecturers')
                   ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('course_lecturer');
    }
}
