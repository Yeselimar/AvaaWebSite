<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application.... Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
/*
$factory->define(avaa\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});
*/

$factory->define(avaa\User::class, function (Faker $faker)
{
    return [
        'name' => $faker->firstName,
        'email' => $faker->unique()->email,
        'last_name' => $faker->lastName,
        'password' => bcrypt('123456'), // secret
        'remember_token' => str_random(10),
        'fecha_nacimiento' => $faker->date('Y-m-d','now'),
        'sexo' => $faker->randomElement(array('masculino','femenino')),
        'edad' => $faker->numberBetween($min = 16, $max = 25),
        'cedula' => $faker->unique()->numberBetween($min = 10000000, $max = 35000000),
        'rol' => 'becario',

    ];
});
