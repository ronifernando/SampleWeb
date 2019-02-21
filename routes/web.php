<?php
 
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/prepaid-balance', 'PrepaidController@index')->name('prepaid');
Route::Post('/prepaid-balance', 'PrepaidController@store')->name('prepaidstore');

Route::get('/product', 'ProductController@index')->name('product');
Route::Post('/product', 'ProductController@store')->name('productstore');

Route::get('/success', 'OrderController@success')->name('success');

Route::get('/payment', 'OrderController@payment')->name('payment');
Route::post('/payment', 'OrderController@payment')->name('payment');

Route::any('/addpayment', 'OrderController@addpayment')->name('addpayment');

Route::get('/orders', 'OrderController@index')->name('orderhistory');
Route::post('/orders', 'OrderController@search')->name('orderhistory');

View::composer(['home','orders.*'], function ($view) {
    $order = \App\Order::where('user_id', Auth::user()->id)->where('paidstatus', 0)->count();
    View::share('ordercount', $order);
});