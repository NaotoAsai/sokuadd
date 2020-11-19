<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログアウト
     *
     * @test
     */
    public function logoutTest()
    {
        factory(\App\Models\User::class)->create();
        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/v1/logout');

        $response->assertStatus(200);
    }
}
