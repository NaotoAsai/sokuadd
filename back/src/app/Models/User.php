<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        // JWT トークンに保存する ID を返す
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        // JWT トークンに埋め込む追加の情報を返す
        return [];
    }

    public function incomeAndExpenditures()
    {
        return $this->hasMany('App\Models\IncomeAndExpenditure');
    }

    public function incomeAndExpenditureClasses()
    {
        return $this->hasMany('App\Models\IncomeAndExpenditureClass');
    }

    /**
     * メールアドレスを更新する
     *
     * @param string $email
     * @return void
     */
    protected static function updateEmail(string $userId, string $email)
    {
        User::where('id', $userId)
            ->update(['email' => $email]);
    }

    /**
     * パスワードを再発行する
     *
     * @param string $email
     * @param string $password
     * @return User
     */
    protected static function updatePasswordByReset(string $email, string $password)
    {
        $user = User::where('email', $email)
            ->first();
        $user->password = bcrypt($password);
        $user->save();
        // アクセストークン発行用にユーザー情報を返す
        return $user;
    }

    /**
     * パスワードを更新する
     *
     * @param string $password
     * @return void
     */
    public function updatePasswordByEdit(string $password)
    {
        $this->password = bcrypt($password);
        $this->save();
    }
}
