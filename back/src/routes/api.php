<?php

use App\Http\Controllers\IncomeAndExpenditureClassController;
use App\Http\Controllers\IncomeAndExpenditureController;
use App\Models\IncomeAndExpenditure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\In;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// 全てのユーザー
Route::group([
    'middleware' =>['api'],
    'prefix' => 'v1'
], function(){
    Route::post('refresh', 'AuthController@refresh');
});

// 未ログインユーザー
Route::group([
    'middleware' =>['api', 'guest'],
    'prefix' => 'v1'
], function(){
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
});

// ログイン済みユーザー
Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'v1'
], function () {
    Route::post('logout', 'AuthController@logout');
    Route::get('user', 'AuthController@me');
    // Route::put('user', 'AuthController@updateName');

    // Route::get('classes', 'IncomeAndExpenditureClassController@getClasses');
    // Route::post('class', 'IncomeAndExpenditureClassController@createClass');
    // Route::put('class', 'IncomeAndExpenditureClassController@updateClass');
    // Route::delete('class', 'IncomeAndExpenditureClassController@deleteClass');

    // Route::post('incomeandexpenditure', 'IncomeAndExpenditureController@createIncomeAndExpenditure');
    // Route::get('incomeandexpenditures', 'IncomeAndExpenditureController@getIncomeAndExpenditures');
    // Route::put('incomeandexpenditure', 'IncomeAndExpenditureController@updateIncomeAndExpenditure');
    // Route::delete('incomeandexpenditure', 'IncomeAndExpenditureController@deleteIncomeAndExpenditure');
    // Route::get('incomeandexpenditurebyclass', 'IncomeAndExpenditureController@getIncomeAndExpenditureByClass');

});
