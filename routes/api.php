<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:api']], function () {


    Route::get('/users', 'UserController@index');

    Route::post('/goods', 'GoodController@store');

    Route::get('/goods', 'GoodController@index');

    Route::get('/stations', 'StationController@index');

    Route::post('/image', 'GoodController@upload');

    //Route::post('/tasks','ShipmentController@store');

    Route::post('/tasks', 'ShipmentController@teststore'); //test

    Route::get('/tasklist', 'ShipmentController@index');

    Route::get('/myTask', 'ShipmentController@getMyTask');

    Route::get('/preparedTasks', 'ShipmentController@getPreparedTasks');

    Route::post('/checkin', 'ShipmentController@checkin');

    Route::post('/checkout', 'ShipmentController@checkout');

    Route::Get('/runnerHistory', 'AchivementController@index');

    Route::Get('/medalStatus', 'AchivementController@medalStatus');

    Route::Post('/statusCancel', 'ShipmentController@statusCancel');
});

Route::post('/register', 'UserController@register');

Route::post('/login', 'UserController@login');

Route::post('/stations', 'StationController@store');

Route::post('/test/goods', 'GoodController@test');//test
