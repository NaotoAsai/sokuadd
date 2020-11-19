<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ユーザー名を変更出来る
     *
     * @test
     */
    public function editNameTest()
    {
        factory(\App\Models\User::class)->create();
        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/user', ['name' => 'editedName']);

        $response->assertStatus(200);
    }

    /**
     * nameの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfNameTest()
    {
        factory(\App\Models\User::class)->create();
        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/user', [
            'name' => '最大文字数の32文字超え、ああああああああああああああああああああああああああああああ'
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'ユーザー名は32文字以下にしてください。'
            ]);
    }

    /**
     * ユーザー情報が返ってくる
     *
     * @test
     */
    public function meTest()
    {
        factory(\App\Models\User::class)->create();
        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->getJson('/api/v1/user');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
            ]);
    }

    /**
     * 退会が出来る
     *
     * @test
     */
    public function withdrawTest()
    {
        factory(\App\Models\User::class)->create();
        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->deleteJson('/api/v1/user', ['password' => 'test0000']);

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
        ])->deleteJson('/api/v1/user', ['password' => 'test']);

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
        ])->deleteJson('/api/v1/user', ['password' => 'test1111']);

        $response
            ->assertJson([
                'message' => '※パスワードが間違っています。'
            ])
            ->assertStatus(401);
    }
}
