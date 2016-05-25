<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', 'HomeController@index');

Route::get('/courses', 'CoursesController@index');
Route::get('/courses/{course}', 'CoursesController@show');
Route::post('/courses', 'CoursesController@store');

Route::get('/lessons/{lesson}', 'LessonsController@show');

Route::post('/lessons/{lesson}/files', 'LessonFilesController@store');
Route::delete('/files/{file}', 'LessonFilesController@delete');
