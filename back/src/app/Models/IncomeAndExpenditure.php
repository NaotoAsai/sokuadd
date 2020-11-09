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

    /**
     * 当該収支情報を持つユーザー情報を取得
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * 当該収支情報が所属している分類情報を取得
     *
     * @return IncomeAndExpenditureClass
     */
    public function incomeAndExpenditureClass()
    {
        return $this->belongsTo('App\Models\IncomeAndExpenditureClass');
    }

    /**
     * 認証ユーザーの指定月の収支情報を取得
     *
     * @param integer $year
     * @param integer $month
     * @return array
     */
    public static function getIncomeAndExpenditures(int $year, int $month)
    {
        // 当該ユーザーの指定月の収支情報コレクションを取得
        $incomeAndExpenditures = IncomeAndExpenditure::where('user_id', Auth::id())
            ->select('id', 'income_and_expenditure_class_id', 'type', 'target_date', 'amount', 'comment')
            ->whereYear('target_date', $year)
            ->whereMonth('target_date', $month)
            ->orderBy('target_date', 'asc')
            ->get();

        // 取得した収支情報コレクションを成形する
        $formedIncomeAndExpenditures = static::formationIncomeAndExpenditures($incomeAndExpenditures);

        return $formedIncomeAndExpenditures;

    }

    /**
     * フロント(Vuetifyカレンダー)用配列データの形成
     *
     * @param Collection $incomeAndExpenditures
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
            'id' => '',// DBのprimary key
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
        foreach ($incomeAndExpenditures as $incomeAndExpenditure) {
            // 収支データの日付（日付によって分岐するため）
            $currentDay = $incomeAndExpenditure->target_date;
            // 日付が前回と同じなら
            if ($currentDay === $prevDay || $prevDay === '') {
                // ひとつの収支データ作成
                // 収支データのタイプによって分岐
                switch ($incomeAndExpenditure->type) {
                    // タイプが収入の時
                    case 0:
                        $dayIncomeData['id'] = $incomeAndExpenditure->id;
                        if ($incomeAndExpenditure->income_and_expenditure_class_id !== null) {
                            $dayIncomeData['className'] = IncomeAndExpenditureClass::select('name')->where('id', $incomeAndExpenditure->income_and_expenditure_class_id)->first()->name;
                        } else {
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
                    // タイプが支出の時
                    case 1:
                        $dayExpenditureData['id'] = $incomeAndExpenditure->id;
                        if ($incomeAndExpenditure->income_and_expenditure_class_id !== null) {
                            $dayExpenditureData['className'] = IncomeAndExpenditureClass::select('name')->where('id', $incomeAndExpenditure->income_and_expenditure_class_id)->first()->name;
                        } else {
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
            // 日付が前回と異なれば
            } else {
                if (!empty($dayIncomeItems)) {
                    // 前回日付の収入データをまとめて最終配列にプッシュ
                    $dispDayIncomeData['name'] = "+$dayIncomeTotalAmount";
                    $dispDayIncomeData['start'] = $prevDay;
                    $dispDayIncomeData['items'] = $dayIncomeItems;
                    $formedIncomeAndExpenditures[] = $dispDayIncomeData;
                }
                if (!empty($dayExpenditureItems)) {
                    // 前回日付の支出データをまとめて最終配列にプッシュ
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


                // ひとつの収支データ作成
                // 収支データのタイプによって分岐
                switch ($incomeAndExpenditure->type) {
                    // タイプが収入の時
                    case 0:
                        $dayIncomeData['id'] = $incomeAndExpenditure->id;
                        if ($incomeAndExpenditure->income_and_expenditure_class_id !== null) {
                            $dayIncomeData['className'] = IncomeAndExpenditureClass::select('name')->where('id', $incomeAndExpenditure->income_and_expenditure_class_id)->first()->name;
                        } else {
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
                    // タイプが支出の時
                    case 1:
                        $dayExpenditureData['id'] = $incomeAndExpenditure->id;
                        if ($incomeAndExpenditure->income_and_expenditure_class_id !== null) {
                            $dayExpenditureData['className'] = IncomeAndExpenditureClass::select('name')->where('id', $incomeAndExpenditure->income_and_expenditure_class_id)->first()->name;
                        } else {
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

        // 繰り返し終了後、配列の最後の日付のデータを最終配列にプッシュ
        if (!empty($dayIncomeItems)) {
            // 最後の日付の収入データをまとめて最終配列にプッシュ
            $dispDayIncomeData['name'] = "+$dayIncomeTotalAmount";
            $dispDayIncomeData['start'] = $prevDay;
            $dispDayIncomeData['items'] = $dayIncomeItems;
            $formedIncomeAndExpenditures[] = $dispDayIncomeData;
        }
        if (!empty($dayExpenditureItems)) {
            // 最後の日付の支出データをまとめて最終配列にプッシュ
            $dispDayExpenditureData['name'] = "-$dayExpenditureTotalAmount";
            $dispDayExpenditureData['start'] = $prevDay;
            $dispDayExpenditureData['items'] = $dayExpenditureItems;
            $formedIncomeAndExpenditures[] = $dispDayExpenditureData;
        }

        return $formedIncomeAndExpenditures;
    }

    /**
     * 認証ユーザーの指定月の分類別収支を取得
     *
     * @param integer $year
     * @param integer $month
     * @return array
     */
    public static function getIncomeAndExpendituresByClass(int $year, int $month)
    {
        // コレクション帰ってくる
        $incomeAndExpenditures = IncomeAndExpenditure::where('user_id', Auth::id())
            ->select('income_and_expenditure_class_id', 'type', 'amount')
            ->whereYear('target_date', $year)
            ->whereMonth('target_date', $month)
            ->orderBy('income_and_expenditure_class_id', 'asc')
            ->orderBy('type', 'asc')// 未分類対策
            ->get();

        // データがない時は何も返さない
        if ($incomeAndExpenditures->isEmpty()) {
            return;
        }

        // 取得した収支情報コレクションを成形する
        $formedIncomeAndExpendituresByClass = static::formationIncomeAndExpendituresByClass($incomeAndExpenditures);

        return $formedIncomeAndExpendituresByClass;
    }

    /**
     * 収支情報コレクションから分類別収支情報配列を成形する
     *
     * @param Collection $incomeAndExpenditures
     * @return array
     */
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
        // 分類ごとのトータルデータ、集計後最終配列にプッシュ
        $totalAmountByClass = [
            'name' => '',
            'amount' => 0,
        ];
        // 繰り返し中の前回分類参照用
        $prevClass = '';
        // 繰り返し中の前回の収支タイプ参照用
        $prevType = '';
        // 分類ごとの合計金額算出用
        $currentClassTotalAmount = 0;
        // 収入合計金額算出用
        $incomeTotalAmount = 0;
        // 支出合計金額算出用
        $expenditureTotalAmount = 0;


        // 収支データをひとつづつ繰り返す
        foreach ($incomeAndExpenditures as $incomeAndExpenditure) {
            // 現在の分類（前回との比較用）
            $currentClass = $incomeAndExpenditure->income_and_expenditure_class_id;
            // 現在の収支タイプ（前回との比較用）
            $currentType = $incomeAndExpenditure->type;

            // 分類が前回と同じかつタイプが前回と同じなら
            // 分類は収支通して一意だが、nullの時に収支判別が出来ないため、タイプ比較も入れている
            if ($currentClass === $prevClass && $currentType === $prevType || $prevClass === '') {
                // 同じ分類の金額を加算していく
                $currentClassTotalAmount += $incomeAndExpenditure->amount;
                // 今回の分類とタイプを記録
                $prevClass = $currentClass;
                $prevType = $currentType;
            // 分類かタイプが前回と違かった時
            } else {
                // タイプによって処理分岐
                switch ($prevType) {
                    // タイプが収入の時
                    case 0:
                        // クラスごとのトータル金額と名前をセット
                        if ($prevClass !== null) {
                            $totalAmountByClass['name'] = IncomeAndExpenditureClass::select('name')->where('id', $prevClass)->first()->name;
                        } else {
                            $totalAmountByClass['name'] = '未分類';
                        }
                        $totalAmountByClass['amount'] = $currentClassTotalAmount;
                        // 最終配列にプッシュ
                        $formedIncomeAndExpendituresByClass['incomes']['classes'][] = $totalAmountByClass;
                        // 収入合計金額に加算する
                        $incomeTotalAmount += $currentClassTotalAmount;
                        break;
                    // タイプが支出の時
                    case 1:
                        //クラスごとのトータル金額と名前をセット
                        if ($prevClass !== null) {
                            $totalAmountByClass['name'] = IncomeAndExpenditureClass::select('name')->where('id', $prevClass)->first()->name;
                        } else {
                            $totalAmountByClass['name'] = '未分類';
                        }
                        $totalAmountByClass['amount'] = $currentClassTotalAmount;
                        // 最終配列にプッシュ
                        $formedIncomeAndExpendituresByClass['expenditures']['classes'][] = $totalAmountByClass;
                        // 支出合計金額に加算する
                        $expenditureTotalAmount += $currentClassTotalAmount;
                        break;
                }
                // 分類別別合計金額をリセットする
                $currentClassTotalAmount = 0;

                // 同じクラスの金額を加算していく
                $currentClassTotalAmount += $incomeAndExpenditure->amount;
                // 今回のクラスとタイプを記録
                $prevClass = $currentClass;
                $prevType = $currentType;
            }
        }

        // 配列の最後のクラスのデータを最終配列にプッシュ
        // タイプによって処理分岐
        switch ($prevType) {
            // タイプが収入の時
            case 0:
                // クラスごとのトータル金額と名前をセット
                if ($prevClass !== null) {
                    $totalAmountByClass['name'] = IncomeAndExpenditureClass::select('name')->where('id', $prevClass)->first()->name;
                } else {
                    $totalAmountByClass['name'] = '未分類';
                }
                $totalAmountByClass['amount'] = $currentClassTotalAmount;
                // 最終配列にプッシュ
                $formedIncomeAndExpendituresByClass['incomes']['classes'][] = $totalAmountByClass;
                // 収入合計金額に加算する
                $incomeTotalAmount += $currentClassTotalAmount;
                break;
            // タイプが支出の時
            case 1:
                //分類ごとのトータル金額と名前をセット
                if ($prevClass !== null) {
                    $totalAmountByClass['name'] = IncomeAndExpenditureClass::select('name')->where('id', $prevClass)->first()->name;
                } else {
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
        // 収入支出合計金額をセット、プラスの場合には＋をつけて返す、0の場合は±をつけて返す
        $totalAmount = $incomeTotalAmount - $expenditureTotalAmount;
        if ($totalAmount > 0) {
            $formedIncomeAndExpendituresByClass['totalAmount'] = "+$totalAmount";
        } elseif ($totalAmount === 0) {
            $formedIncomeAndExpendituresByClass['totalAmount'] = "±$totalAmount";
        } else {
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
