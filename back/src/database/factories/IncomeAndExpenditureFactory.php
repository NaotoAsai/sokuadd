<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\IncomeAndExpenditure;
use Faker\Generator as Faker;

$factory->define(IncomeAndExpenditure::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'income_and_expenditure_class_id' => $faker->numberBetween(1, 15),
        'type' => $faker->numberBetween(0, 1),
        'target_date' => $faker->dateTimeBetween('-30days', 'now')->format('Y-m-d'),
        'amount' => $faker->numberBetween(100, 1000),
        'comment'=> $faker->word
    ];
});
