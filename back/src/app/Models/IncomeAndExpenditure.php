<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\IncomeAndExpenditureClass;

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

    /**
     * 認証ユーザーの指定月の収支情報を取得
     *
     * @param integer $year
     * @param integer $month
     * @return object
     */
    public static function getIncomeAndExpenditures(int $year, int $month)
    {
        $incomeAndExpenditures = IncomeAndExpenditure::where('user_id', Auth::id())
            ->select('id', 'income_and_expenditure_class_id', 'type', 'target_date', 'amount', 'comment')
            ->whereYear('target_date', $year)
            ->whereMonth('target_date', $month)
            ->orderBy('target_date', 'asc')
            ->get();

        $formedIncomeAndExpenditures = static::formationIncomeAndExpenditures($incomeAndExpenditures);

        return $formedIncomeAndExpenditures;

    }

    /**
     * フロント(Vuetifyカレンダー)用配列データの形成
     *
     * @param array $incomeAndExpenditures
     * @return array
     */
    protected static function formationIncomeAndExpenditures($incomeAndExpenditures)
    {
        // 最終的な配列データ
        $formedIncomeAndExpenditures = [];
        // 繰り返し中、前回の日付参照
        $prevDay = '';
        // 当該日の収入データ、完成したら最終配列にプッシュ
        $dispDayIncomeData = [
            'name' => '',
            'start' => '',
            'type' => 0,
            'items' => []
        ];
        // 当該日の支出データ、完成したら最終配列にプッシュ
        $dispDayExpenditureData = [
            'name' => '',
            'start' => '',
            'type' => 1,
            'items' => []
        ];
        // 当該日の収入配列、完成したらdispのitemsにプッシュ
        $dayIncomeItems = [];
        // 当該日の支出配列、完成したらdispのitemsにプッシュ
        $dayExpenditureItems = [];
        // 当該日の支出データ、$dayIncomeImtesにプッシュ
        $dayIncomeData = [
            'id' => '',// dbのprimary key
            'class' => '',// 分類名
            'amount' => '',
            'comment' => ''
        ];
        // 当該日の支出データ、$dayExpenditureImtesにプッシュ
        $dayExpenditureData = [
            'id' => '',// dbのprimary key
            'class' => '',// 分類名
            'amount' => '',
            'comment' => ''
        ];
        // 日毎に収入トータル金額を出す
        $dayIncomeTotalAmount = 0;
        // 日毎に支出トータル金額を出す
        $dayExpenditureTotalAmount = 0;

        // 収支データをひとつづつ繰り返す
        foreach ($incomeAndExpenditures as $incomeAndExpenditure)
        {
            // 収支データの日付
            $currentDay = $incomeAndExpenditure->target_date;
            // 日付が前回と同じなら
            if ($currentDay === $prevDay || $prevDay === '')
            {
                // 収支データのタイプによって分岐
                switch ($incomeAndExpenditure->type)
                {
                    case 0:
                        // ひとつの収入データ作成
                        $dayIncomeData['id'] = $incomeAndExpenditure->id;
                        $dayIncomeData['class'] = IncomeAndExpenditureClass::select('name')->where('id', $incomeAndExpenditure->income_and_expenditure_class_id)->first()->name;
                        $dayIncomeData['amount'] = $incomeAndExpenditure->amount;
                        $dayIncomeData['comment'] = $incomeAndExpenditure->comment;
                        // 日毎の収入データ配列に格納していく
                        $dayIncomeItems[] = $dayIncomeData;
                        // 日毎の収支トータル金額加算
                        $dayIncomeTotalAmount += $incomeAndExpenditure->amount;
                        // 前回日付参照用に今回の日付を記録
                        $prevDay = $incomeAndExpenditure->target_date;
                        break;
                    case 1:
                        // ひとつの支出データ作成
                        $dayExpenditureData['id'] = $incomeAndExpenditure->id;
                        $dayExpenditureData['class'] = IncomeAndExpenditureClass::select('name')->where('id', $incomeAndExpenditure->income_and_expenditure_class_id)->first()->name;
                        $dayExpenditureData['amount'] = $incomeAndExpenditure->amount;
                        $dayExpenditureData['comment'] = $incomeAndExpenditure->comment;
                        // 日毎の収入データ配列に格納していく
                        $dayExpenditureItems[] = $dayExpenditureData;
                        // 日毎の収入トータル金額加算
                        $dayExpenditureTotalAmount += $incomeAndExpenditure->amount;
                        // 前回日付参照用に今回の日付を記録
                        $prevDay = $incomeAndExpenditure->target_date;
                        break;
                }
            } else // 日付が前回と異なれば 
            {
                if (!empty($dayIncomeItems))
                {
                    // 前回日付の収入データをまとめて最終配列にプッシュ
                    $dispDayIncomeData['name'] = "+$dayIncomeTotalAmount";
                    $dispDayIncomeData['start'] = $prevDay;
                    $dispDayIncomeData['items'] = $dayIncomeItems;
                    $formedIncomeAndExpenditures[] = $dispDayIncomeData;
                }
                if (!empty($dayExpenditureItems))
                {
                    // 前回データの支出データをまとめて最終配列にプッシュ
                    $dispDayExpenditureData['name'] = "-$dayExpenditureTotalAmount";
                    $dispDayExpenditureData['start'] = $prevDay;
                    $dispDayExpenditureData['items'] = $dayExpenditureItems;
                    $formedIncomeAndExpenditures[] = $dispDayExpenditureData;
                }
                
                
                // 日毎のデータをリセットしておく
                $dayIncomeItems = [];
                $dayIncomeTotalAmount = 0;
                $dayExpenditureItems = [];
                $dayExpenditureTotalAmount = 0;


                // 収支データのタイプによって分岐
                switch ($incomeAndExpenditure->type)
                {
                    case 0:
                        // ひとつの収入データ作成
                        $dayIncomeData['id'] = $incomeAndExpenditure->id;
                        $dayIncomeData['class'] = IncomeAndExpenditureClass::select('name')->where('id', $incomeAndExpenditure->income_and_expenditure_class_id)->first()->name;
                        $dayIncomeData['amount'] = $incomeAndExpenditure->amount;
                        $dayIncomeData['comment'] = $incomeAndExpenditure->comment;
                        // 日毎の収入データ配列に格納していく
                        $dayIncomeItems[] = $dayIncomeData;
                        // 日毎の収支トータル金額加算
                        $dayIncomeTotalAmount += $incomeAndExpenditure->amount;
                        // 前回日付参照用に今回の日付を記録
                        $prevDay = $incomeAndExpenditure->target_date;
                        break;
                    case 1:
                        // ひとつの支出データ作成
                        $dayExpenditureData['id'] = $incomeAndExpenditure->id;
                        $dayExpenditureData['class'] = IncomeAndExpenditureClass::select('name')->where('id', $incomeAndExpenditure->income_and_expenditure_class_id)->first()->name;
                        $dayExpenditureData['amount'] = $incomeAndExpenditure->amount;
                        $dayExpenditureData['comment'] = $incomeAndExpenditure->comment;
                        // 日毎の収入データ配列に格納していく
                        $dayExpenditureItems[] = $dayExpenditureData;
                        // 日毎の収入トータル金額加算
                        $dayExpenditureTotalAmount += $incomeAndExpenditure->amount;
                        // 前回日付参照用に今回の日付を記録
                        $prevDay = $incomeAndExpenditure->target_date;
                        break;
                }
            }
        }

        return $formedIncomeAndExpenditures;
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
