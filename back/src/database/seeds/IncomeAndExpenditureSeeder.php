<?php

use Illuminate\Database\Seeder;

class IncomeAndExpenditureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\IncomeAndExpenditure::class, 100)->create();
    }
}
