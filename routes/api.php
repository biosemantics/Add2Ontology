<?php

//Route::group([
//        'prefix' => '/v1',
//        'namespace' => 'Api\V1',
//        'middleware' => ['cors'],
//        'as' => 'api.'
//    ], function () {
//    Route::resource('companies', 'CompaniesController', ['except' => ['create', 'edit']]);
//});

Route::group([
        'prefix' => '/v1',
        'namespace' => 'Api\V1',
        'middleware' => ['cors'],
        'as' => 'api.'
    ], function () {

    Route::post('activity-log',                ['as' => 'meta_log',                    'uses' => 'HomeController@saveActivityLog']);

});
