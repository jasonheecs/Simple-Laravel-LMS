<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $name = $faker->name;
    return [
        'name'           => $name,
        'email'          => $faker->safeEmail,
        'password'       => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'avatar'         => 'http://api.adorable.io/avatars/150/' . urlencode($name),
        'company'        => $faker->company
    ];
});

$factory->defineAs(App\User::class, 'superadmin', function (Faker\Generator $faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return $user;
});

$factory->define(App\Course::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(5),
        'image' => 'http://placehold.it/1500x550'
    ];
});

$factory->define(App\Lesson::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence(5),
        'body'  => '<p>' . $faker->text() .'</p>'
    ];
});

$factory->define(App\LessonFile::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(3),
        'url'  => $faker->sentence(12),
        'description' => $faker->text()
    ];
});
