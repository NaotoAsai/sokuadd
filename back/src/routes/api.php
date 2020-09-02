<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' =>['api'], 'prefix' => 'v1'], function(){
    // Route::post('register', 'AuthController@register');
    // Route::post('login', 'AuthController@login');
    Route::get('/', function() {
        return 'Hello Ajax!!';
    });

    // Route::group(['middleware' => 'auth:api'], function() {
    //     Route::post('logout',  'AuthController@logout');
    //     Route::get('refresh', 'AuthController@refresh');
    //     Route::get('user', 'AuthController@me');
    // });
});

Route::group([
    'middleware' => ['api', 'auth:api'],
    'prefix' => 'auth'
 ], function () {
     Route::post('register', 'AuthController@register')->withoutMiddleware(['auth:api']);
     Route::post('login', 'AuthController@login')->withoutMiddleware(['auth:api']);
     Route::post('logout', 'AuthController@logout');
     Route::post('refresh', 'AuthController@refresh')->withoutMiddleware(['auth:api']);
     Route::get('user', 'AuthController@me');
 });
