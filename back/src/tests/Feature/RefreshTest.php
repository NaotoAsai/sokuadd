<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RefreshTest extends TestCase
{
    use RefreshDatabase;

    private $accessToken = '';

    /**
     * アクセストークンリフレッシュテスト(諦めた)
     *
     * @test
     */
    // public function refreshTest()
    // {
    //     $response = $this->post('/api/v1/login', [
    //         'email' => 'test@example.com',
    //         'password' => 'test0000'
    //     ]);
    //     $accessToken = $response['access_token'];

    //     $response = $this->withHeaders([
    //         'Authorization' => 'Bearer ' . $accessToken,
    //     ])->postJson('/api/v1/refresh');

    // }
}
