<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 会員登録できる
     *
     * @test
     */
    public function registerTest()
    {
        $response = $this->post('/api/v1/register', [
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);

        $response
            ->assertStatus(200);
    }

    /**
     * nameの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfNameTest()
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => '最大文字数の32文字超え、ああああああああああああああああああああああああああああああ',
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => 'ユーザー名は32文字以下にしてください。'
            ]);

    }

    /**
     * emailの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfEmailTest()
    {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'test',
            'email' => 'test',
            'password' => 'test0000'
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'email' => 'メールアドレスを正しいメールアドレスにしてください。'
            ]);

    }

    /**
     * passwordの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfPasswordTest()
    {
        $response = $this->postJson('/api/v1/register', [
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

}
