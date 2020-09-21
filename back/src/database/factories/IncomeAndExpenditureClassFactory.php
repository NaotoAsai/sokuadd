<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\IncomeAndExpenditureClass;
use Faker\Generator as Faker;

$factory->define(IncomeAndExpenditureClass::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'name' => $faker->word,
        'type' => $faker->numberBetween(0, 1)
    ];
});
