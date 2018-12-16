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

Route::get('/{term}/{user}/{ontology}', 'HomeController@index')->name('home');
Route::get('/wizard/{term}/{user}/{ontology}', 'HomeController@wizard')->name('wizard');
Route::get('/quick-form/{term}/{user}/{ontology}', 'HomeController@quickForm')->name('quick-form');
Route::get('/web-protege/{term}/{user}/{ontology}', 'HomeController@webProtege')->name('web-protege');
Route::get('/terms-wiki/{term}/{user}/{ontology}', 'HomeController@termsWiki')->name('terms-wiki');
//
//Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
//    Route::get('activity_log', 'ActivityLogController@index')->name('activity.index');
//});
