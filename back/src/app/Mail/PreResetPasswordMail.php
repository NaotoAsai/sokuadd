<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
        ->from(config('mail.from.address')) // 送信元
        ->subject('パスワード再発行登録準備完了のお知らせ') // メールタイトル
        ->view('emails.reset_password') // メール本文のテンプレート
        ->with(['url' => config('app.url') . '/resetpassword?genericToken=' . $this->token]);  // withでセットしたデータをviewへ渡す
    }
}
