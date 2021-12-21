<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        "name" => $faker->sentence(3),
        "schedule" => $faker->time,
        "start_date" => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '-1 months'),
        "end_date" => $faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
    ];
});
