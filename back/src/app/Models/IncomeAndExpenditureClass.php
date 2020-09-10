<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class IncomeAndExpenditureClass extends Model
{
    protected $fillable = [
        'user_id', 'name', 'type'
    ];

    public function incomeAndExpenditures()
    {
        return $this->hasMany('App\Models\IncomeAndExpenditure');
    }

    public static function createClass(string $name, int $type)
    {
        IncomeAndExpenditureClass::create([
            'user_id' => Auth::id(),
            'name' => $name,
            'type' => $type
        ]);
    }

    public static function updateClass(int $id, string $name, int $type)
    {
        IncomeAndExpenditureClass::where('user_id', Auth::id())
            ->where('type', $type)
            ->where('id', $id)
            ->update(['name' => $name]);
    }
}
