<?php

namespace Tests\Feature;

use App\Models\IncomeAndExpenditure;
use App\Models\IncomeAndExpenditureClass;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IncomeAndExpendituresTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * 認証ユーザーの指定月の収支情報を取得出来る
     *
     * @test
     */
    public function getIncomeAndExpendituresTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->getJson('/api/v1/incomeandexpenditures?year='.$year.'&month='.$month);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([[
                'name',
                'start',
                'type',
                'items' => [[
                    'id',
                    'className',
                    'classId',
                    'amount',
                    'comment'
                ]]
            ]]);
    }

    /**
     * 収支情報取得時のyearの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfYearByGetTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = 9999;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->getJson('/api/v1/incomeandexpenditures?year='.$year.'&month='.$month);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'year' => '年の書式が正しくありません。'
            ]);
    }

    /**
     * 収支情報取得時のmonthの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfMonthByGetTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = 99;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken
        ])->getJson('/api/v1/incomeandexpenditures?year='.$year.'&month='.$month);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'month' => '月の書式が正しくありません。'
            ]);
    }

    /**
     * 認証ユーザーの指定月の収支情報を取得出来る、収支情報がない場合
     *
     * @test
     */
    public function getIncomeAndExpendituresTestOfEmpty()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->getJson('/api/v1/incomeandexpenditures?year='.$year.'&month='.$month);

        $response
            ->assertStatus(200)
            ->assertExactJson([]);
    }

    /**
     * 収入を追加する、全項目を今日に追加
     *
     * @test
     */
    public function createIncomeAndExpenditureByIncomeTest()
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
        ])->postJson('/api/v1/incomeandexpenditures', [
            'classId' => IncomeAndExpenditureClass::where('type', 0)->first()->id,
            'targetDate' => Carbon::now()->format('Y-m-d'),
            'amount' => '1000',
            'comment' => 'コメント',
            'type' => 0
        ]);

        $response
            ->assertStatus(200);
    }

    /**
     * 支出を追加する、全項目を今日に追加
     *
     * @test
     */
    public function createIncomeAndExpenditureByExpenditureTest()
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
        ])->postJson('/api/v1/incomeandexpenditures', [
            'classId' => IncomeAndExpenditureClass::where('type', 1)->first()->id,
            'targetDate' => Carbon::now()->format('Y-m-d'),
            'amount' => '1000',
            'comment' => 'コメント',
            'type' => 1
        ]);

        $response
            ->assertStatus(200);
    }

    /**
     * 収入を追加する、クラスとコメントがNULL
     *
     * @test
     */
    public function createIncomeAndExpenditureIncludingNullTest()
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
        ])->postJson('/api/v1/incomeandexpenditures', [
            'classId' => '',
            'targetDate' => Carbon::now()->format('Y-m-d'),
            'amount' => '1000',
            'comment' => '',
            'type' => 0
        ]);

        $response
            ->assertStatus(200);
    }

    /**
     * 収入追加時のclassIdの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfClassIdByCreateTest()
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
        ])->postJson('/api/v1/incomeandexpenditures', [
            'classId' => 9999,
            'targetDate' => Carbon::now()->format('Y-m-d'),
            'amount' => '1000',
            'comment' => 'コメント',
            'type' => 0
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'classId' => '分類が存在しません'
            ]);
    }

    /**
     * 収入追加時のtargetDateの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfTargetDateByCreateTest()
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
        ])->postJson('/api/v1/incomeandexpenditures', [
            'classId' => IncomeAndExpenditureClass::where('type', 0)->first()->id,
            'targetDate' => Carbon::now()->format('Y/m/d'),
            'amount' => '1000',
            'comment' => 'コメント',
            'type' => 0
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'targetDate' => '日付は"Y-m-d"書式と一致していません。'
            ]);
    }

    /**
     * 収入追加時のamountの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfAmountByCreateTest()
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
        ])->postJson('/api/v1/incomeandexpenditures', [
            'classId' => IncomeAndExpenditureClass::where('type', 0)->first()->id,
            'targetDate' => Carbon::now()->format('Y-m-d'),
            'amount' => '100.5',
            'comment' => 'コメント',
            'type' => 0
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'amount' => '金額は整数にしてください。'
            ]);
    }

    /**
     * 収入追加時のcommentの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfCommentByCreateTest()
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
        ])->postJson('/api/v1/incomeandexpenditures', [
            'classId' => IncomeAndExpenditureClass::where('type', 0)->first()->id,
            'targetDate' => Carbon::now()->format('Y-m-d'),
            'amount' => '1000',
            'comment' => 'コメントaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'type' => 0
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'comment' => 'コメントは64文字以下にしてください。'
            ]);
    }

    /**
     * 収入追加時のtypeの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfTypeByCreateTest()
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
        ])->postJson('/api/v1/incomeandexpenditures', [
            'classId' => IncomeAndExpenditureClass::where('type', 0)->first()->id,
            'targetDate' => Carbon::now()->format('Y-m-d'),
            'amount' => '1000',
            'comment' => 'コメント',
            'type' => 2
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'classId' => '分類が不正です',
                'type' => '収入or支出の選択の書式が正しくありません。'
            ]);
    }

    /**
     * 収支を編集する、先月の一番最初の収支
     *
     * @test
     */
    public function editIncomeAndExpenditureTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/incomeandexpenditures', [
            'id' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->id,
            'classId' => '',
            'targetDate' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->target_date,
            'amount' => '2000',
            'comment' => '',
            'year' => $year,
            'month' => $month
        ]);

        $response
            ->assertStatus(200);
    }

    /**
     * 収支編集時のidの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfIdByEditTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/incomeandexpenditures', [
            'id' => 9999,
            'classId' => '',
            'targetDate' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->target_date,
            'amount' => '2000',
            'comment' => '',
            'year' => $year,
            'month' => $month
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'id' => '収支情報が存在しません'
            ]);
    }

    /**
     * 収支編集時のclassIdの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfClassIdByEditTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/incomeandexpenditures', [
            'id' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->id,
            'classId' => 9999,
            'targetDate' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->target_date,
            'amount' => '2000',
            'comment' => '',
            'year' => $year,
            'month' => $month
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'classId' => '分類が存在しません'
            ]);
    }

    /**
     * 収支編集時のtargetDateの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfTargetDateByEditTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/incomeandexpenditures', [
            'id' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->id,
            'classId' => '',
            'targetDate' => Carbon::now()->format('Y/m/d'),
            'amount' => '2000',
            'comment' => '',
            'year' => $year,
            'month' => $month
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'targetDate' => '日付は"Y-m-d"書式と一致していません。'
            ]);
    }

    /**
     * 収支編集時のamountの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfAmountByEditTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/incomeandexpenditures', [
            'id' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->id,
            'classId' => '',
            'targetDate' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->target_date,
            'amount' => 'あああ',
            'comment' => '',
            'year' => $year,
            'month' => $month
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'amount' => '金額は整数にしてください。'
            ]);
    }

    /**
     * 収支編集時のcommentの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfCommentByEditTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/incomeandexpenditures', [
            'id' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->id,
            'classId' => '',
            'targetDate' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->target_date,
            'amount' => '2000',
            'comment' => 'コメントaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
            'year' => $year,
            'month' => $month
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'comment' => 'コメントは64文字以下にしてください。'
            ]);
    }

    /**
     * 収支編集時のyearの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfYearByEditTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/incomeandexpenditures', [
            'id' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->id,
            'classId' => '',
            'targetDate' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->target_date,
            'amount' => '2000',
            'comment' => '',
            'year' => 9999,
            'month' => $month
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'year' => '年の書式が正しくありません。'
            ]);
    }

    /**
     * 収支編集時のmonthの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfMonthByEditTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->putJson('/api/v1/incomeandexpenditures', [
            'id' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->id,
            'classId' => '',
            'targetDate' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->target_date,
            'amount' => '2000',
            'comment' => '',
            'year' => $year,
            'month' => 99
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'month' => '月の書式が正しくありません。'
            ]);
    }

    /**
     * 収支を削除する、先月の一番最初の収支
     *
     * @test
     */
    public function deleteIncomeAndExpenditureTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->deleteJson('/api/v1/incomeandexpenditures', [
            'id' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->id,
            'year' => $year,
            'month' => $month
        ]);

        $response
            ->assertStatus(200);
    }

    /**
     * 収支削除時のidの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfIdByDeleteTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->deleteJson('/api/v1/incomeandexpenditures', [
            'id' => 9999,
            'year' => $year,
            'month' => $month
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'id' => '収支情報が存在しません'
            ]);
    }

    /**
     * 収支削除時のidの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfYearByDeleteTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->deleteJson('/api/v1/incomeandexpenditures', [
            'id' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->id,
            'year' => 9999,
            'month' => $month
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'year' => '年の書式が正しくありません。'
            ]);
    }

    /**
     * 収支削除時のidの値のバリデーションエラーチェック
     *
     * @test
     */
    public function validationErrorOfMonthByDeleteTest()
    {
        factory(\App\Models\User::class)->create();
        factory(\App\Models\IncomeAndExpenditureClass::class, 15)->create(['user_id' => User::all()->first()->id]);
        factory(\App\Models\IncomeAndExpenditure::class, 400)->create(['user_id' => User::all()->first()->id]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'test0000'
        ]);
        $accessToken = $response['access_token'];

        // 先月
        $lastMonth = Carbon::now()->subMonth();
        $year = $lastMonth->year;
        $month = $lastMonth->month;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
        ])->deleteJson('/api/v1/incomeandexpenditures', [
            'id' => IncomeAndExpenditure::whereYear('target_date', $year)->whereMonth('target_date', $month)->first()->id,
            'year' => $year,
            'month' => 99
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors([
                'month' => '月の書式が正しくありません。'
            ]);
    }
}
