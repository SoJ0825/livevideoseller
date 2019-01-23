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

Route::post('/login', 'UserController@check');

Route::put('/user/update', 'UserController@update');

Route::get('/live_video/lists', 'ProductController@liveVideoList');

Route::get('/products/list/{live_video_id}', 'ProductController@productList');

Route::post('/orders/create', 'OrderController@orderCreate');

Route::post('buyer/orders/{live_video_id?}', 'OrderController@buyerOrder');