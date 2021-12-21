<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [
        "firstname" => $this->faker->firstName,
        "lastname" => $this->faker->lastName,
        "email" => $this->faker->safeEmail,
        "birthdate" => $this->faker->date,
    ];
});
