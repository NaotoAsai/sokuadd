<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * パスワード変更が出来る
     *
     * @test
     */
    public function editPasswordTest()
    {
        factory(\App\Models\User::class)->create();
        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/password', [
            'password' => 'test0000',
            'newPassword' => 'test1111'
        ]);

        $response->assertStatus(200);
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
        ])->putJson('/api/v1/password', [
            'password' => 'test',
            'newPassword' => 'test1111'
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'password' => 'パスワードは8文字以上にしてください。'
            ]);

    }

    /**
     * newPasswordの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfNewPasswordTest()
    {
        factory(\App\Models\User::class)->create();
        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/password', [
            'password' => 'test0000',
            'newPassword' => 'test'
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'newPassword' => '新しいパスワードは8文字以上にしてください。'
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
        ])->putJson('/api/v1/password', [
            'password' => 'test00001',
            'newPassword' => 'test1111'
        ]);

        $response
            ->assertJson([
                'message' => '※現在のパスワードが間違っています。'
            ])
            ->assertStatus(401);
    }
}
