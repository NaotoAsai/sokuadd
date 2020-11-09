<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\AuthController\RegisterRequest;
use App\Http\Requests\AuthController\LoginRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthController extends Controller
{
    /**
     * 新規ユーザー登録
     *
     * @param RegisterRequest $request
     * @return void
     */
    public function register(RegisterRequest $request)
    {
        // 新規ユーザー登録、登録完了後フロントでloginAPIにアクセスするので戻り値なし
        User::register($request->name, $request->email, $request->password);
    }

    /**
     * ログインする
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        // トークン生成
        $token = $this->createAccessToken($request->email, $request->password);

        // ユーザーが存在しなかったら401 Unauthorized
        if (!$token) {
            $res = response()->json([
                'message' => '※ログインできませんでした、入力内容を確認してください。'
            ], 401);
            throw new HttpResponseException($res);
        }

        // トークンの戻り値成形
        return $this->respondWithToken($token);
    }

    /**
     * アクセストークンを生成する
     *
     * @param string $email
     * @param string $password
     * @return string
     */
    protected function createAccessToken(string $email, string $password)
    {
        // attemptは通常真偽値を返すが、JWTではアクセストークンを返す
        return Auth::guard('api')->attempt(['email' => $email, 'password' => $password]);
    }

    /**
     * アクセストークンの戻り値を成形する
     *
     * @param string $token
     * @return JsonResponse
     */
    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',// いらないかも
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * ユーザー情報を返す
     *
     * @return JsonResponse
     */
    public function me() {
        return response()->json(Auth::guard('api')->user());
    }

    /**
     * 期限切れのアクセストークンを更新する
     *
     * @return JsonResponse
     */
    public function refresh() {
        return $this->respondWithToken(Auth::guard('api')->refresh());
    }

    /**
     * ログアウトする
     *
     * @return void
     */
    public function logout() {
        Auth::guard('api')->logout();
    }
}
