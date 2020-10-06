<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserController\EditNameRequest;
use App\Http\Requests\UserController\EditPasswordRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        if (!Hash::check($request->password, Auth::user()->password)) {
            $res = response()->json([
                'status' => 401,
                'message' => '※現在のパスワードが間違っています。',
            ], 401);
            throw new HttpResponseException($res);
        }
        User::updatePasswordByEdit($request->newPassword);
    }
}
