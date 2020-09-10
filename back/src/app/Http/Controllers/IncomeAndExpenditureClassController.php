<?php

namespace App\Http\Controllers;

use App\Models\IncomeAndExpenditureClass;
use App\Http\Requests\IncomeAndExpenditureClassController\CreateClassRequest;
use App\Http\Requests\IncomeAndExpenditureClassController\UpdateClassRequest;
class IncomeAndExpenditureClassController extends Controller
{
    public function createClass(CreateClassRequest $request)
    {
        IncomeAndExpenditureClass::createClass($request->name, $request->type);
    }

    public function updateClass(UpdateClassRequest $request)
    {
        IncomeAndExpenditureClass::updateClass($request->id, $request->name, $request->type);
    }
}
