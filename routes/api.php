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

Route::prefix('fake')->group(function () {
    //login
    Route::post('login', 'FakeController@login');
    Route::post('firstlogin', 'FakeController@firstlogin');
    Route::put('user/update', 'FakeController@userupdate');
    //buyer
    Route::get('live_video/lists', 'FakeController@livevideolists');
    //seller
    Route::post('product/preparelist', 'FakeController@productpreparelist');
    Route::post('product/set', 'FakeController@productset');
    Route::put('product/update', 'FakeController@productupdate');
    Route::delete('product/delete', 'FakeController@productdelete');
    Route::post('product/sell', 'FakeController@productsell');
    Route::post('product/stopsell', 'FakeController@productstopsell');
    Route::post('live_video/start', 'FakeController@livevideostart');
    Route::post('live_video/stop', 'FakeController@livevideostop');
    Route::post('seller/video_list', 'FakeController@sellervideolist');
    Route::post('seller/orders', 'FakeController@sellerorders');
    Route::get('errormessage', 'FakeController@errormessage');
});

//seller
Route::middleware('validateToken')->group(function () {
    Route::post('/product/preparelist', 'ProductController@prepare');
    Route::post('/product/set', 'ProductController@setNewProduct');
    Route::put('/product/update', 'ProductController@updateProduct');
    Route::delete('/product/delete', 'ProductController@delete');
    Route::post('/product/sell', 'ProductController@sell');
    Route::post('/product/stopsell', 'ProductController@stopSell');

    //live video
    Route::post('/live_video/start', 'LiveVideoController@start');
    Route::post('/live_video/stop', 'LiveVideoController@stop');
    Route::post('/seller/video_list', 'LiveVideoController@show');
    Route::post('/seller/orders/{live_video_id?}', 'OrderController@showWithLiveVideoId');
});

