<?php

namespace App\Models;

use App\Exceptions\User\EmailNotFoundException;
use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Auth;

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

    /**
     * 当該ユーザーが持つ収支情報を取得
     *
     * @return Collection
     */
    public function incomeAndExpenditures()
    {
        return $this->hasMany('App\Models\IncomeAndExpenditure');
    }

    /**
     * 当該ユーザーが持つ収支分類情報を取得
     *
     * @return Collection
     */
    public function incomeAndExpenditureClasses()
    {
        return $this->hasMany('App\Models\IncomeAndExpenditureClass');
    }

    /**
     * 新規ユーザー登録
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @return void
     */
    public static function register(string $name, string $email, string $password)
    {
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)
        ]);
    }

    /**
     * メールアドレスを更新する
     *
     * @param string $email
     * @return void
     */
    public static function updateEmail(string $userId, string $email)
    {
        User::where('id', $userId)
            ->update(['email' => $email]);
    }

    /**
     * パスワードを再発行する
     *
     * @param string $email
     * @param string $password
     * @return void
     */
    public static function updatePasswordByReset(string $email, string $password)
    {
        $user = User::where('email', $email)
            ->first();
        if ($user === null) {
            // メールアドレスが登録されていない
            throw new EmailNotFoundException();
        }
        $user->password = bcrypt($password);
        $user->save();
    }

    /**
     * パスワードを更新する
     *
     * @param string $password
     * @return void
     */
    public static function updatePasswordByEdit(string $password)
    {
        User::where('id', Auth::id())
            ->update(['password' => bcrypt($password)]);
    }

    /**
     * ユーザー名を更新する
     *
     * @param string $name
     * @return void
     */
    public static function updateName(string $name)
    {
        User::where('id', Auth::id())
            ->update(['name' => $name]);
    }

}
