<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeAndExpenditureClass extends Model
{
    public function incomeAndExpenditures()
    {
        return $this->hasMany('App\Models\IncomeAndExpenditure');
    }
}
