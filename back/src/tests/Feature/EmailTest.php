<?php

namespace Tests\Feature;

use App\Models\Token;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmailTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * メールアドレス変更準備、入力されたメールアドレスにメールを送信出来る
     *
     * @test
     */
    public function preEditEmailTest()
    {
        factory(\App\Models\User::class)->create();
        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/v1/email', [
            'email' => 'test2@example.com',
            'password' => 'test0000'
        ]);

        $response->assertStatus(200);
    }

    /**
     * emailの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfEmailTest()
    {
        factory(\App\Models\User::class)->create();
        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/v1/email', [
            'email' => 'test2',
            'password' => 'test0000'
        ]);

        $response
            ->assertJsonValidationErrors([
                'email' => "メールアドレスを正しいメールアドレスにしてください。"
            ])
            ->assertStatus(422);
    }

    /**
     * emailの値のバリデーションエラーチェック、メールアドレスが既に存在している時
     *
     * @test
     */
    public function alreadyExistsEmailTest()
    {
        factory(\App\Models\User::class)->create();
        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/v1/email', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);

        $response
            ->assertJsonValidationErrors([
                'email' => "メールアドレスは既に存在します。"
            ])
            ->assertStatus(422);
    }

    /**
     * passwordの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfPasswordTest()
    {
        factory(\App\Models\User::class)->create();
        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/v1/email', [
            'email' => 'test2@example.com',
            'password' => 'test'
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'password' => 'パスワードは8文字以上にしてください。'
            ]);
    }

    /**
     * パスワード不一致時のエラーチェック
     *
     * @test
     */
    public function passwordMismatchTest()
    {
        factory(\App\Models\User::class)->create();
        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/v1/email', [
            'email' => 'test2@example.com',
            'password' => 'test1111'
        ]);

        $response
            ->assertJson([
                'message' => '※現在のパスワードが間違っています。'
            ])
            ->assertStatus(401);
    }

    /**
     * メールアドレス変更
     *
     * @test
     */
    public function editEmailTest()
    {
        factory(\App\Models\User::class)->create();

        $token = Token::registerTokenEditEmail('test2@example.com', User::all()->first()->id);

        $response = $this->getJson('/api/v1/email?genericToken='.$token);

        $response
            ->assertStatus(200);
    }
}
