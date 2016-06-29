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

$factory->define(SmartBots\User::class, function (Faker\Generator $faker) {
    return [
        'username' => 'demodemo',
        'name' => 'Đê văn mô',
        'email' => 'demo@de.mo',
        'password' => bcrypt('demodemo'),
        'remember_token' => str_random(10),
        'avatar' => 'http://loremflickr.com/200/200/?'.str_random(5)
    ];
});
