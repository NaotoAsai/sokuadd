<?php

namespace Tests\Feature;

use App\Models\Token;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * パスワード再発行手続き、指定したメールアドレスにメール送信まで出来ているか
     *
     * @test
     */
    public function PreResetPasswordTest()
    {

        $response = $this->postJson('/api/v1/resetpassword', [
            'email' => 'test@example.com'
        ]);

        $response
            ->assertStatus(200);
    }

    /**
     * トークン確認(メールのURLから入った画面表示用)
     *
     * @test
     */
    public function PassResetPasswordTest()
    {
        factory(\App\Models\User::class)->create();

        $token = Token::registerTokenResetPassword('test@example.com');

        $response = $this->getJson('/api/v1/resetpassword?genericToken='.$token);

        $response
            ->assertStatus(200);
    }

    /**
     * パスワードリセット
     *
     * @test
     */
    public function ResetPasswordTest()
    {
        factory(\App\Models\User::class)->create();

        $token = Token::registerTokenResetPassword('test@example.com');

        $response = $this->putJson('/api/v1/resetpassword', [
            'genericToken' => $token,
            'password' => 'test1111'
        ]);

        $response
            ->assertStatus(200);
    }
}
