<?php

namespace App\Http\Controllers;

use App\Events\PreResetPasswordEvent;
use App\Exceptions\User\EmailNotFoundException;
use App\Exceptions\Token\TokenExpiredException;
use App\Exceptions\Token\TokenNotFoundException;
use App\Http\Controllers\AuthController;
use App\Http\Requests\ResetPasswordController\PassResetPasswordRequest;
use App\Http\Requests\ResetPasswordController\PreResetPasswordRequest;
use App\Http\Requests\ResetPasswordController\ResetPasswordRequest;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends AuthController
{
    /**
     * パスワード再発行手続き
     *
     * @param PreResetPasswordRequest $request
     * @return void
     */
    public function preResetPassword(PreResetPasswordRequest $request)
    {
        try {
            $token = Token::registerTokenResetPassword($request->email);
        } catch (\Exception $e) {
            abort(500);
        }
        // メールの送信
        event(new PreResetPasswordEvent($request->email, $token));
    }

    /**
     * トークン確認(メールのURLから入った画面表示用)
     *
     * @param PassResetPasswordRequest $request
     * @return void
     */
    public function passResetPassword(PassResetPasswordRequest $request)
    {
        try {
            // トークンの有効性チェック
            $this->checkToken($request->genericToken);
        } catch (HttpResponseException $e) {
            throw $e;
        } catch (\Exception $e) {
            abort(500);
        }
    }

    /**
     * パスワード再発行
     *
     * @param ResetPasswordRequest $request
     * @return Json
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        try {

            DB::beginTransaction();
            // トークンの有効性チェック
            $tokenRecode = $this->checkToken($request->genericToken);

            // パスワード更新
            User::updatePasswordByReset($tokenRecode->email, $request->password);
            // 戻り値のメールアドレス
            // $email = $tokenRecode->email;
            // トークン削除
            $tokenRecode->delete();

            DB::commit();

        } catch (EmailNotFoundException $e) {
            $res = response()->json([
                'message' => 'このメールアドレスは登録されていません。'
            ], 403);
            throw new HttpResponseException($res);
        } catch (HttpResponseException $e) {
            DB::rollback();
            throw $e;
        } catch (\Exception $e) {
            DB::rollback();
            abort(500);
        }

        // リセット後自動ログインさせるため、AuthModuleの仕様でメールアドレスを返す
        // return response()->json(['email' => $email]);
    }

    /**
     * トークンの有効性をチェックする
     *
     * @param string $token
     * @return Token
     */
    protected function checkToken(string $token): Token
    {
        try {
            return Token::checkTokenResetPassword($token);
        } catch (TokenNotFoundException $e) {
            $res = response()->json([
                'message' => 'トークンが不正です。再度最初からやり直してください。'
            ], 400);
            throw new HttpResponseException($res);
        } catch (TokenExpiredException $e) {
            $res = response()->json([
                'message' => 'トークンの有効期限が切れています。再度最初からやり直してください。'
            ], 422);
            throw new HttpResponseException($res);
        }
    }
}