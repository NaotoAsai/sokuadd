<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\IncomeAndExpenditure;
use App\Models\IncomeAndExpenditureClass;
use Faker\Generator as Faker;

$factory->define(IncomeAndExpenditure::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'income_and_expenditure_class_id' => $faker->numberBetween(IncomeAndExpenditureClass::all()->first()->id, IncomeAndExpenditureClass::all()->first()->id + 14),
        // 公式参考、引数に今回挿入データの配列を受け取り、その外部キーを元に、モデルへアクセスし他カラムの情報を取ってくる
        'type' => function (array $incomeAndExpenditure) {
            return App\Models\IncomeAndExpenditureClass::find($incomeAndExpenditure['income_and_expenditure_class_id'])->type;
        },
        'target_date' => $faker->dateTimeBetween('-3month', 'now')->format('Y-m-d'),
        'amount' => $faker->numberBetween(100, 2000),
        'comment'=> $faker->word
    ];
});
