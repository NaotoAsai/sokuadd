<?php

namespace App\Models;

use App\Exceptions\Token\TokenExpiredException;
use App\Exceptions\Token\TokenNotFoundException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Token extends Model
{
    protected $fillable = [
        'token', 'type', 'email', 'user_id'
    ];


    /**
     * タイプ：メールアドレス変更
     */
    const TYPE_EDIT_EMAIL = "edit_email";

    /**
     * タイプ：パスワードリセット
     */
    const TYPE_EDIT_PASSWORD = "reset_password";

    /**
     * トークンの有効期限(30分)
     */
    const TOKEN_EXPIRES = 1800;

    /**
     * メールアドレス変更のトークンを作成
     *
     * @param string $email
     * @param string $userId
     * @return string
     */
    public static function registerTokenEditEmail(string $email, string $userId): string {
        return static::registerToken(static::TYPE_EDIT_EMAIL, $email, null, $userId);
    }

    /**
     * メールアドレス変更のトークンをチェック
     *
     * @param string $token
     * @return Token
     * @throws TokenNotFoundException
     * @throws TokenExpiredException
     */
    public static function checkTokenEditEmail(string $token): Token {
        return static::checkToken(static::TYPE_EDIT_EMAIL, $token);
    }

    /**
     * パスワード再発行のトークンを作成
     *
     * @param string $email
     * @param string $userId
     * @return string
     */
    public static function registerTokenResetPassword(string $email): string {
        return static::registerToken(static::TYPE_EDIT_PASSWORD, $email, null, null);
    }

    /**
     * パスワード再発行のトークンをチェック
     *
     * @param string $token
     * @return Token
     * @throws TokenNotFoundException
     * @throws TokenExpiredException
     */
    public static function checkTokenResetPassword(string $token): Token {
        return static::checkToken(static::TYPE_EDIT_PASSWORD, $token);
    }

    /**
     * トークンを登録する
     *
     * @param string $type
     * @param string $email
     * @param string|null $name
     * @param string|null $userId
     * @return string
     */
    protected static function registerToken(string $type, string $email, ?string $name, ?string $userId): string {

        // すでにトークンテーブルに存在する場合は削除する
        static::deleteTokenByEmail($type,$email);

        // トークンの作成する
        $token = static::createToken($email);
        //　トークンを保存する
        Token::create(['name' => $name, 'email' => $email, 'token' => $token, 'user_id' => $userId, 'type' => $type]);

        return $token;
    }

    /**
     * トークンの削除(Emailをキーにして)
     *
     * @param string $type
     * @param string $email
     * @return void
     */
    protected static function deleteTokenByEmail(string $type, string $email) {
        //トークンの削除は、メールアドレスをキーにして削除する。
        Token::where('email', $email)->where('type', $type)->delete();
    }

    /**
     * トークンの削除(Tokenをキーにして)
     *
     * @param string $type
     * @param string $token
     * @return void
     */
    protected static function deleteTokenByToken(string $type, string $token) {
        Token::where('token', $token)->where('type', $type)->delete();
    }

    /**
     * トークンの作成
     * @param string $str
     * @return string トークン
     */
    protected static function createToken(string $str): string{
        return hash_hmac(
            'sha256',
            Str::random(40) . $str,
            config('app.key')
        );
    }

    /**
     * トークンの有効性チェック
     *
     * @param string $type
     * @param string $token
     * @return Token
     * @throws TokenNotFoundException
     * @throws TokenExpiredException
     */
    protected static function checkToken(string $type, string $token): Token {
        $tokenRecord = Token::where('token', $token)->where('type', $type)->first();
        if (!$tokenRecord) {
            // トークンが存在しない
            throw new TokenNotFoundException();
        }
        if ($tokenRecord->tokenExpired()) {
            // トークンの期限切れ
            throw new TokenExpiredException();
        }
        return $tokenRecord;
    }

    /**
     * トークンの期限切れチェック
     * (期限切れならture)
     *
     * @return boolean
     * @throws \Exception
     */
    protected function tokenExpired(): bool {
        return Carbon::parse($this->created_at)->addSeconds(static::TOKEN_EXPIRES)->isPast();
    }
}
