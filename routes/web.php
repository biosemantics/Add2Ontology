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


//Heng's code
//Route::get('/{term}', 'HomeController@index')->name('home');
//Route::get('/wizard/{term}', 'HomeController@wizard')->name('wizard');
//Route::get('/quick-form/{term}', 'HomeController@quickForm')->name('quick-form');
//Route::get('/web-protege/{term}', 'HomeController@webProtege')->name('web-protege');
//Route::get('/terms-wiki/{term}', 'HomeController@termsWiki')->name('terms-wiki');

//Hong's try
Route::get('/{user}/{ontology}/{term}', 'HomeController@index')->name('home');
Route::get('/wizard/{user}/{ontology}/{term}', 'HomeController@wizard')->name('wizard');
Route::get('/quick-form/{user}/{ontology}/{term}', 'HomeController@quickForm')->name('quick-form');
Route::get('/web-protege/{user}/{ontology}/{term}', 'HomeController@webProtege')->name('web-protege');
Route::get('/terms-wiki/{user}/{ontology}/{term}', 'HomeController@termsWiki')->name('terms-wiki');

//
//Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
//    Route::get('activity_log', 'ActivityLogController@index')->name('activity.index');
//});
