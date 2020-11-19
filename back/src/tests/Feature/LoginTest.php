<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログイン成功時、指定のJSONデータが返ってきているか
     *
     * @test
     */
    public function loginTest()
    {
        factory(\App\Models\User::class)->create();

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'expires_in'
            ]);
    }

    /**
     * emailの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfEmailTest()
    {
        factory(\App\Models\User::class)->create();

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test',
            'password' => 'test0000'
        ]);

        $response
            ->assertJsonValidationErrors([
                'email' => "メールアドレスを正しいメールアドレスにしてください。"
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
        $response = $this->postJson('/api/v1/login', [
            'name' => 'test',
            'email' => 'test@example.com',
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

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test1111'
        ]);

        $response
            ->assertJson([
                'message' => '※ログインできませんでした、入力内容を確認してください。'
            ])
            ->assertStatus(401);
    }
}
