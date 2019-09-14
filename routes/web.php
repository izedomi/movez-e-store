<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/top-up', 'HomeController@top_up');
Route::post('/gift-cash', 'HomeController@gift_cash');
Route::get('/buy-product', 'HomeController@buy_product');
Route::post('/checkout', 'HomeController@checkout');
Route::post('/pay', 'HomeController@pay');
Route::get('/top-up-success', 'HomeController@top_up_successful');
Route::get('/checkout-success', 'HomeController@check_out_success');
