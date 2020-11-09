<?php

namespace App\Http\Controllers;

use App\Events\PreEditEmailEvent;
use App\Exceptions\Token\TokenExpiredException;
use App\Exceptions\Token\TokenNotFoundException;
use App\Http\Requests\EmailController\PreEditEmailRequest;
use App\Http\Requests\EmailController\EditEmailRequest;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmailController extends Controller
{

    /**
     * メールアドレス変更準備
     *
     * @param PreEditEmailRequest $request
     * @return void
     */
    public function preEditEmail(PreEditEmailRequest $request)
    {
        // 現在のパスワードチェック
        if (!Hash::check($request->password, Auth::user()->password)) {
            $res = response()->json([
                'status' => 401,
                'message' => '※現在のパスワードが間違っています。',
            ], 401);
            throw new HttpResponseException($res);
        }
        
        try {
            DB::beginTransaction();
            $token = Token::registerTokenEditEmail($request->email, $request->user()->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            abort(500);
        }
        // メールの送信
        event(new PreEditEmailEvent($request->email, $token));
    }

    /**
     * メールアドレスを更新する
     *
     * @param EditEmailRequest $request
     * @return void
     */
    public function editEmail(EditEmailRequest $request)
    {
        $user = null;
        try {
            DB::beginTransaction();

            // トークンの有効性チェック
            $tokenRecode = $this->checkToken($request->genericToken);

            // メールアドレス更新
            User::updateEmail($tokenRecode->user_id, $tokenRecode->email,);

            // トークン削除
            $tokenRecode->delete();

            DB::commit();
        } catch (HttpResponseException $e) {
            DB::rollback();
            throw $e;
        } catch (\Exception $e) {
            DB::rollback();
            abort(500);
        }
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
            return Token::checkTokenEditEmail($token);
        } catch (TokenNotFoundException $e) {
            $res = response()->json([
                'message' => '※トークンが不正です。再度最初からやり直してください。'
            ], 422);
            throw new HttpResponseException($res);
        } catch (TokenExpiredException $e) {
            $res = response()->json([
                'message' => '※トークンの有効期限が切れています。再度最初からやり直してください。'
            ], 422);
            throw new HttpResponseException($res);
        }
    }

}
