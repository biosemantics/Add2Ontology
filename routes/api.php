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

   # Original Route::post('activity-log/{term}',                ['as' => 'meta_log',                    'uses' => 'HomeController@saveActivityLog']);
   Route::post('activity-log/{term}/{user}/{ontology}',     ['as' => 'meta_log', 'uses' => 'HomeController@saveActivityLog']);

});
