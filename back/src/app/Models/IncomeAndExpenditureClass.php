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

    public static function getClasses()
    {
        $incomeClasses = IncomeAndExpenditureClass::select('id', 'name')
            ->where('user_id', Auth::id())
            ->where('type', 0)
            ->get();
        $expenditureClasses = IncomeAndExpenditureClass::select('id', 'name')
            ->where('user_id', Auth::id())
            ->where('type', 1)
            ->get();
        
        return response()->json([
            'incomeClasses' => $incomeClasses,
            'expenditureClasses' => $expenditureClasses
        ]);
    }

    public static function createClass(string $name, int $type)
    {
        return IncomeAndExpenditureClass::create([
            'user_id' => Auth::id(),
            'name' => $name,
            'type' => $type
        ]);
    }

    public static function updateClass(int $id, string $name)
    {
        IncomeAndExpenditureClass::where('id', $id)
            ->update(['name' => $name]);
    }

    public static function deleteClass(int $id)
    {
        IncomeAndExpenditureClass::where('id', $id)
            ->delete();
    }
}
