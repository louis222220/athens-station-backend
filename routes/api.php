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


    Route::get('/users','UserController@index');

    Route::post('/goods','GoodController@store');

    Route::get('/goods','GoodController@index');

    Route::get('/stations','StationController@index');

    Route::post('/image','GoodController@upload');

    Route::get('/tasks','ShipmentController@task');

});

Route::post('/register','UserController@register');

Route::post('/login','UserController@login');

Route::post('/stations','StationController@store');


Route::post('/test/goods','GoodController@test');//test

