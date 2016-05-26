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
Route::post('/courses', 'CoursesController@store');
Route::get('/courses/{course}', ['as' => 'course', 'uses' => 'CoursesController@show']);

Route::get('/lessons/{lesson}', ['as' => 'lesson', 'uses' => 'LessonsController@show']);
Route::patch('/lessons/{lesson}', 'LessonsController@update');
Route::delete('/lessons/{lesson}', 'LessonsController@delete');

Route::post('/lessons/{lesson}/files', 'LessonFilesController@store');
Route::patch('/files/{file}', 'LessonFilesController@update');
Route::delete('/files/{file}', 'LessonFilesController@delete');
Route::get('/files/{file}/edit', 'LessonFilesController@edit');

