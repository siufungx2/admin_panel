<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Role::class, function (Faker $faker) {
    return [
        'role' => $faker->randomElement(['admin', 'moderator'])
    ];
});