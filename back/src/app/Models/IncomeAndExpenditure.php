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
            'className' => '', // 分類名
            'classId' => '', //分類ID、更新時に必要
            'amount' => '',
            'comment' => ''
        ];
        // 当該日の支出データ、$dayExpenditureImtesにプッシュ
        $dayExpenditureData = [
            'id' => '',// dbのprimary key
            'className' => '', // 分類名
            'classId' => '', //分類ID、更新時に必要
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
                        if ($incomeAndExpenditure->income_and_expenditure_class_id !== null)
                        {
                            $dayIncomeData['className'] = IncomeAndExpenditureClass::select('name')->where('id', $incomeAndExpenditure->income_and_expenditure_class_id)->first()->name;
                        } else
                        {
                            $dayIncomeData['className'] = '未分類';
                        }
                        $dayIncomeData['classId'] = $incomeAndExpenditure->income_and_expenditure_class_id;
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
                        if ($incomeAndExpenditure->income_and_expenditure_class_id !== null)
                        {
                            $dayExpenditureData['className'] = IncomeAndExpenditureClass::select('name')->where('id', $incomeAndExpenditure->income_and_expenditure_class_id)->first()->name;
                        } else
                        {
                            $dayExpenditureData['className'] = '未分類';
                        }
                        $dayExpenditureData['classId'] = $incomeAndExpenditure->income_and_expenditure_class_id;
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
                        if ($incomeAndExpenditure->income_and_expenditure_class_id !== null)
                        {
                            $dayIncomeData['className'] = IncomeAndExpenditureClass::select('name')->where('id', $incomeAndExpenditure->income_and_expenditure_class_id)->first()->name;
                        } else
                        {
                            $dayIncomeData['className'] = '未分類';
                        }
                        $dayIncomeData['classId'] = $incomeAndExpenditure->income_and_expenditure_class_id;
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
                        if ($incomeAndExpenditure->income_and_expenditure_class_id !== null)
                        {
                            $dayExpenditureData['className'] = IncomeAndExpenditureClass::select('name')->where('id', $incomeAndExpenditure->income_and_expenditure_class_id)->first()->name;
                        } else
                        {
                            $dayExpenditureData['className'] = '未分類';
                        }
                        $dayExpenditureData['classId'] = $incomeAndExpenditure->income_and_expenditure_class_id;
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

        // 配列の最後の日付のデータを最終配列にプッシュ
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

        return $formedIncomeAndExpenditures;
    }

    public static function getIncomeAndExpendituresByClass(int $year, int $month)
    {
        $incomeAndExpenditures = IncomeAndExpenditure::where('user_id', Auth::id())
            ->select('income_and_expenditure_class_id', 'type', 'amount')
            ->whereYear('target_date', $year)
            ->whereMonth('target_date', $month)
            ->orderBy('income_and_expenditure_class_id', 'asc')
            ->orderBy('type', 'asc')// 未分類対策
            ->get();

        $formedIncomeAndExpendituresByClass = static::formationIncomeAndExpendituresByClass($incomeAndExpenditures);

        // return $incomeAndExpenditures;
        return $formedIncomeAndExpendituresByClass;
    }

    protected static function formationIncomeAndExpendituresByClass($incomeAndExpenditures)
    {
        // 最終的な配列データ
        $formedIncomeAndExpendituresByClass = [
            'incomes' => [
                'classes' => [],
                'totalAmount' => 0
            ],
            'expenditures' => [
                'classes' => [],
                'totalAmount' => 0
            ],
            'totalAmount' => 0
        ];
        // クラスごとのトータルデータ、集計後最終配列にプッシュ
        $totalAmountByClass = [
            'name' => '',
            'amount' => 0,
        ];
        $prevClass = '';
        $prevType = '';
        $currentClassTotalAmount = 0;
        $incomeTotalAmount = 0;
        $expenditureTotalAmount = 0;
        foreach ($incomeAndExpenditures as $incomeAndExpenditure)
        {
            $currentClass = $incomeAndExpenditure->income_and_expenditure_class_id;
            $currentType = $incomeAndExpenditure->type;
            // クラスが前回と同じかつタイプが前回と同じなら
            // クラスは収支通して一意だが、nullの時に収支判別が出来ないため、タイプ比較も入れている
            if ($currentClass === $prevClass && $currentType === $prevType || $prevClass === '')
            {
                // 同じクラスの金額を加算していく
                $currentClassTotalAmount += $incomeAndExpenditure->amount;
                // 今回のクラスとタイプを記録
                $prevClass = $currentClass;
                $prevType = $currentType;         
            } else // クラスかタイプが前回と違かった時
            {
                // typeによって処理分岐
                switch ($prevType)
                {
                    case 0:
                        // クラスごとのトータル金額と名前をセット
                        if ($prevClass !== null)
                        {
                            $totalAmountByClass['name'] = IncomeAndExpenditureClass::select('name')->where('id', $prevClass)->first()->name;
                        } else
                        {
                            $totalAmountByClass['name'] = '未分類';
                        }
                        $totalAmountByClass['amount'] = $currentClassTotalAmount;
                        // 最終配列にプッシュ
                        $formedIncomeAndExpendituresByClass['incomes']['classes'][] = $totalAmountByClass;
                        // 収入合計金額に加算する
                        $incomeTotalAmount += $currentClassTotalAmount;
                        break;
                    case 1:
                        //クラスごとのトータル金額と名前をセット
                        if ($prevClass !== null)
                        {
                            $totalAmountByClass['name'] = IncomeAndExpenditureClass::select('name')->where('id', $prevClass)->first()->name;
                        } else
                        {
                            $totalAmountByClass['name'] = '未分類';
                        }
                        $totalAmountByClass['amount'] = $currentClassTotalAmount;
                        // 最終配列にプッシュ
                        $formedIncomeAndExpendituresByClass['expenditures']['classes'][] = $totalAmountByClass;
                        // 支出合計金額に加算する
                        $expenditureTotalAmount += $currentClassTotalAmount;
                        break;
                }
                // クラス別合計金額をリセットする
                $currentClassTotalAmount = 0;

                // 同じクラスの金額を加算していく
                $currentClassTotalAmount += $incomeAndExpenditure->amount;
                // 今回のクラスとタイプを記録
                $prevClass = $currentClass;
                $prevType = $currentType;
            }
        }

        // 配列の最後のクラスのデータを最終配列にプッシュ
        // typeによって処理分岐
        switch ($prevType)
        {
            case 0:
                // クラスごとのトータル金額と名前をセット
                if ($prevClass !== null)
                {
                    $totalAmountByClass['name'] = IncomeAndExpenditureClass::select('name')->where('id', $prevClass)->first()->name;
                } else
                {
                    $totalAmountByClass['name'] = '未分類';
                }
                $totalAmountByClass['amount'] = $currentClassTotalAmount;
                // 最終配列にプッシュ
                $formedIncomeAndExpendituresByClass['incomes']['classes'][] = $totalAmountByClass;
                // 収入合計金額に加算する
                $incomeTotalAmount += $currentClassTotalAmount;
                break;
            case 1:
                //クラスごとのトータル金額と名前をセット
                if ($prevClass !== null)
                {
                    $totalAmountByClass['name'] = IncomeAndExpenditureClass::select('name')->where('id', $prevClass)->first()->name;
                } else
                {
                    $totalAmountByClass['name'] = '未分類';
                }
                $totalAmountByClass['amount'] = $currentClassTotalAmount;
                // 最終配列にプッシュ
                $formedIncomeAndExpendituresByClass['expenditures']['classes'][] = $totalAmountByClass;
                // 支出合計金額に加算する
                $expenditureTotalAmount += $currentClassTotalAmount;
                break;
        }

        // 収入合計金額をセット
        $formedIncomeAndExpendituresByClass['incomes']['totalAmount'] = $incomeTotalAmount;
        // 支出合計金額をセット
        $formedIncomeAndExpendituresByClass['expenditures']['totalAmount'] = $expenditureTotalAmount;
        // 収入支出合計金額をセット、プラスの場合には＋をつけて返す
        $totalAmount = $incomeTotalAmount - $expenditureTotalAmount;
        if ($totalAmount > 0)
        {
            $formedIncomeAndExpendituresByClass['totalAmount'] = "+$totalAmount";
        } else
        {
            $formedIncomeAndExpendituresByClass['totalAmount'] = "$totalAmount";
        }
        
        return $formedIncomeAndExpendituresByClass;
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
