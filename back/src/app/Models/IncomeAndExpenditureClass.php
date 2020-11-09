<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class IncomeAndExpenditureClass extends Model
{
    protected $fillable = [
        'user_id', 'name', 'type'
    ];

    /**
     * 当該収支分類がもつ収支情報を取得
     *
     * @return Collection
     */
    public function incomeAndExpenditures()
    {
        return $this->hasMany('App\Models\IncomeAndExpenditure');
    }

    /**
     * 当該収支情報がもつユーザー情報を取得
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 認証ユーザーが持つ収支分類情報を取得
     *
     * @return array
     */
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

        $classes = [
            'incomeClasses' => $incomeClasses,
            'expenditureClasses' => $expenditureClasses
        ];
        
        return $classes;
    }

    /**
     * 分類情報の新規作成
     *
     * @param string $name
     * @param integer $type
     * @return IncomeAndExpenditureClass
     */
    public static function createClass(string $name, int $type)
    {
        // idを返すためリターンする
        return IncomeAndExpenditureClass::create([
            'user_id' => Auth::id(),
            'name' => $name,
            'type' => $type
        ]);
    }

    /**
     * 分類名更新
     *
     * @param integer $id
     * @param string $name
     * @return void
     */
    public static function updateClass(int $id, string $name)
    {
        IncomeAndExpenditureClass::where('id', $id)
            ->update(['name' => $name]);
    }

    /**
     * 分類名削除
     *
     * @param integer $id
     * @return void
     */
    public static function deleteClass(int $id)
    {
        IncomeAndExpenditureClass::where('id', $id)
            ->delete();
    }
}
