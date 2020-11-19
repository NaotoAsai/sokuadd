<?php

namespace Tests\Feature;

use App\Models\IncomeAndExpenditureClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class incomeAndExpenditureClassesTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * 収入と支出の分類情報が複数件取得出来る
     *
     * @test
     */
    public function getClassesTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->getJson('/api/v1/incomeandexpenditure_classes');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'incomeClasses' => [['id', 'name']],
                'expenditureClasses' => [['id', 'name']]
            ]);
    }

    /**
     * 分類情報取得、空の場合
     *
     * @test
     */
    public function getClassesOfEmpty()
    {
        factory(\App\Models\User::class)->create();

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->getJson('/api/v1/incomeandexpenditure_classes');

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'incomeClasses' => [],
                'expenditureClasses' => []
            ]);
    }

    /**
     * 収入分類を追加する、idが返ってくる
     *
     * @test
     */
    public function createClassByIncomeTest()
    {
        factory(\App\Models\User::class)->create();

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/v1/incomeandexpenditure_classes', [
            'name' => '収入分類名',
            'type' => '0'
        ]);

        $response
            ->assertStatus(200);

        $this->assertTrue(is_numeric($response->original));
    }

    /**
     * 支出分類を追加する、idが返ってくる
     *
     * @test
     */
    public function createClassByExpenditureTest()
    {
        factory(\App\Models\User::class)->create();

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/v1/incomeandexpenditure_classes', [
            'name' => '支出分類名',
            'type' => '1'
        ]);

        $response
            ->assertStatus(200);

        $this->assertTrue(is_numeric($response->original));
    }

    /**
     * 分類名追加時のnameの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfNameByCreateTest()
    {
        factory(\App\Models\User::class)->create();

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/v1/incomeandexpenditure_classes', [
            'name' => '最大文字数の32文字超え、ああああああああああああああああああああああああああああああ',
            'type' => '0'
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => '分類名は32文字以下にしてください。'
            ]);
    }

    /**
     * 分類名追加時のtypeの値のバリデーションエラーチェック、0,1以外の不正な値
     *
     * @test
     */
    public function validationErrorOfTypeTest()
    {
        factory(\App\Models\User::class)->create();

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->postJson('/api/v1/incomeandexpenditure_classes', [
            'name' => '収入分類名',
            'type' => '2'
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'type' => '収入or支出の選択の書式が正しくありません。'
            ]);
    }

    /**
     * 分類名を編集できる
     *
     * @test
     */
    public function editClassNameTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/incomeandexpenditure_classes', [
            'id' => IncomeAndExpenditureClass::all()->first()->id,
            'name' => '編集済み分類名'
        ]);

        $response
            ->assertStatus(200);
    }

    /**
     * 分類名編集時のidの値のバリデーションエラーチェック、当該ユーザーに存在しないidだった場合
     *
     * @test
     */
    public function validationErrorOfIdByEditTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/incomeandexpenditure_classes', [
            'id' => 9999,
            'name' => '編集済み分類名'
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'id' => '分類が存在しません'
            ]);
    }

    /**
     * 分類名編集時のnameの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfNameByEditTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);

        $response = $this->post('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/incomeandexpenditure_classes', [
            'id' => IncomeAndExpenditureClass::all()->first()->id,
            'name' => '最大文字数の32文字超え、ああああああああああああああああああああああああああああああ'
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'name' => '分類名は32文字以下にしてください。'
            ]);
    }

    /**
     * 分類名を削除できる
     *
     * @test
     */
    public function deleteClassNameTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->deleteJson('/api/v1/incomeandexpenditure_classes', [
            'id' => IncomeAndExpenditureClass::all()->first()->id
        ]);

        $response
            ->assertStatus(200);
    }

    /**
     * 分類名削除時のidの値のバリデーションエラーチェック、当該ユーザーに存在しないidだった場合
     *
     * @test
     */
    public function validationErrorOfIdByDeleteTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->deleteJson('/api/v1/incomeandexpenditure_classes', [
            'id' => 9999
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'id' => '分類が存在しません'
            ]);
    }
}
