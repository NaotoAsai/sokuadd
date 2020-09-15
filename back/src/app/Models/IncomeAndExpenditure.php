<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class IncomeAndExpenditure extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'income_and_expenditure_class_id',
        'target_date',
        'amount',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function incomeAndExpenditureClass()
    {
        return $this->belongsTo('App\Models\IncomeAndExpenditureClass');
    }

    // ダミーデータ制作後これ
    public function getIncomeAndExpenditures()
    {

    }

    /**
     * 収支情報の新規作成
     *
     * @param integer $type
     * @param integer|null $classId
     * @param string $targetDate
     * @param integer $amount
     * @param string|null $comment
     * @return void
     */
    public static function createIncomeAndExpenditure(
        int $type,
        ?int $classId,
        string $targetDate,
        int $amount,
        ?string $comment
    ) {
        IncomeAndExpenditure::create([
            'user_id' => Auth::id(),
            'type' => $type,
            'income_and_expenditure_class_id' => $classId,
            'target_date' => $targetDate,
            'amount' => $amount,
            'comment' => $comment
        ]);
    }

    /**
     * 収支情報の更新
     *
     * @param integer $id
     * @param integer|null $classId
     * @param string $targetDate
     * @param integer $amount
     * @param string|null $comment
     * @return void
     */
    public static function updateIncomeAndExpenditure(
        int $id,
        ?int $classId,
        string $targetDate,
        int $amount,
        ?string $comment
    ) {
        IncomeAndExpenditure::where('id', $id)
            ->update([
                'income_and_expenditure_class_id' => $classId,
                'target_date' => $targetDate,
                'amount' => $amount,
                'comment' => $comment
            ]);
    }
    
    /**
     * 収支情報の削除
     *
     * @param integer $id
     * @return void
     */
    public static function deleteIncomeAndExpenditure(int $id)
    {
        IncomeAndExpenditure::where('id', $id)
            ->delete();
    }
}
