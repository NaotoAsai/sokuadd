<?php

namespace App\Http\Controllers;

use App\Models\IncomeAndExpenditureClass;
use App\Http\Requests\IncomeAndExpenditureClassController\CreateClassRequest;
use App\Http\Requests\IncomeAndExpenditureClassController\EditClassNameRequest;
use App\Http\Requests\IncomeAndExpenditureClassController\DeleteClassRequest;

class IncomeAndExpenditureClassController extends Controller
{
    /**
     *  当該ユーザーの分類一覧取得
     *
     * @return array
     */
    public function getClasses()
    {
        $classes = IncomeAndExpenditureClass::getClasses();

        return $classes;
    }

    /**
     * 新規分類作成
     *
     * @param CreateClassRequest $request
     * @return integer
     */
    public function createClass(CreateClassRequest $request)
    {
        $response = IncomeAndExpenditureClass::createClass($request->name, $request->type);
        // idだけ返す
        return $response->id;
    }


    /**
     * 既存の分類名の編集
     *
     * @param EditClassNameRequest $request
     * @return void
     */
    public function editClassName(EditClassNameRequest $request)
    {
        IncomeAndExpenditureClass::updateClass($request->id, $request->name);
    }

    /**
     * 分類名を削除
     *
     * @param DeleteClassRequest $request
     * @return void
     */
    public function deleteClass(DeleteClassRequest $request)
    {
        IncomeAndExpenditureClass::deleteClass($request->id);
    }
}
