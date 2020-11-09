<?php

namespace App\Http\Controllers;

use App\Models\IncomeAndExpenditure;
use App\Http\Requests\IncomeAndExpenditureController\CreateIncomeAndExpenditureRequest;
use App\Http\Requests\IncomeAndExpenditureController\EditIncomeAndExpenditureRequest;
use App\Http\Requests\IncomeAndExpenditureController\DeleteIncomeAndExpenditureRequest;
use App\Http\Requests\IncomeAndExpenditureController\GetIncomeAndExpendituresRequest;
use App\Http\Requests\IncomeAndExpenditureController\GetIncomeAndExpendituresByClassRequest;

class IncomeAndExpenditureController extends Controller
{
    /**
     * 認証ユーザーの指定月の収支情報を返す
     *
     * @param GetIncomeAndExpendituresRequest $request
     * @return array
     */
    public function getIncomeAndExpenditures(GetIncomeAndExpendituresRequest $request)
    {
        $incomeAndExpenditures = IncomeAndExpenditure::getIncomeAndExpenditures($request->year, $request->month);

        return $incomeAndExpenditures;
    }

    /**
     * 認証ユーザーの指定月の分類別収支情報を返す
     * @param GetIncomeAndExpendituresByClassRequest $request
     * @return array
     */
    public function getIncomeAndExpendituresByClass(GetIncomeAndExpendituresByClassRequest $request)
    {
        $incomeAndExpendituresByClass = IncomeAndExpenditure::getIncomeAndExpendituresByClass($request->year, $request->month);

        return $incomeAndExpendituresByClass;
    }

    /**
     * 収支情報の新規作成
     *
     * @param CreateIncomeAndExpenditureRequest $request
     * @return void
     */
    public function createIncomeAndExpenditure(CreateIncomeAndExpenditureRequest $request)
    {
        IncomeAndExpenditure::createIncomeAndExpenditure(
            $request->type,
            $request->classId,
            $request->targetDate,
            $request->amount,
            $request->comment
        );
    }

    /**
     * 収支情報の編集
     *
     * @param EditIncomeAndExpenditureRequest $request
     * @return void
     */
    public function editIncomeAndExpenditure(EditIncomeAndExpenditureRequest $request)
    {
        IncomeAndExpenditure::updateIncomeAndExpenditure(
            $request->id,
            $request->classId,
            $request->targetDate,
            $request->amount,
            $request->comment
        );

        // 再描画用に新しいデータを返す
        return IncomeAndExpenditure::getIncomeAndExpenditures($request->year, $request->month);
    }
    
    /**
     * 収支情報の削除
     *
     * @param DeleteIncomeAndExpenditureRequest $request
     * @return void
     */
    public function deleteIncomeAndExpenditure(DeleteIncomeAndExpenditureRequest $request)
    {
        IncomeAndExpenditure::deleteIncomeAndExpenditure($request->id);

        // 再描画用に新しいデータを返す
        return IncomeAndExpenditure::getIncomeAndExpenditures($request->year, $request->month);
    }
}
