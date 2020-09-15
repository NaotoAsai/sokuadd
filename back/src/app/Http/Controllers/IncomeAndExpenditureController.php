<?php

namespace App\Http\Controllers;

use App\Models\IncomeAndExpenditure;
use App\Http\Requests\IncomeAndExpenditureController\CreateIncomeAndExpenditureRequest;
use App\Http\Requests\IncomeAndExpenditureController\EditIncomeAndExpenditureRequest;
use App\Http\Requests\IncomeAndExpenditureController\DeleteIncomeAndExpenditureRequest;

class IncomeAndExpenditureController extends Controller
{
    // ダミーデータ作成後これ
    public function getIncomeAndExpenditures()
    {

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
    }
}
