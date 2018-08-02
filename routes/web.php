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
//
//Route::get('/', function () {
//    return view('home');
//});
//
//Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/wizard', 'HomeController@wizard')->name('wizard');
Route::get('/quick-form', 'HomeController@quickForm')->name('quick-form');
Route::get('/web-protege', 'HomeController@webProtege')->name('web-protege');
Route::get('/terms-wiki', 'HomeController@termsWiki')->name('terms-wiki');
//
//Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
//    Route::get('activity_log', 'ActivityLogController@index')->name('activity.index');
//});
