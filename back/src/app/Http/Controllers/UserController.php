<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserController\EditNameRequest;
use App\Http\Requests\UserController\EditPasswordRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * ユーザー名変更
     *
     * @param EditNameRequest $request
     * @return void
     */
    public function editName(EditNameRequest $request)
    {
        User::updateName($request->name);
    }

    /**
     * パスワード変更
     *
     * @param EditPasswordRequest $request
     * @return void
     */
    public function editPassword(EditPasswordRequest $request)
    {
        User::updatePasswordByEdit($request->newPassword);
    }
}
