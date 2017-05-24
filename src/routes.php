<?php

Route::group(['prefix' => 'flow', 'namespace' => 'WuTongWan\Flow\Http\Controllers'], function () {

    Route::get('/', ['uses' => 'FlowController@index', 'as' => 'flow-index']);
    Route::get('index', ['uses' => 'FlowController@index', 'as' => 'flow-index']);

    Route::get('type', ['uses' => 'FlowController@getType', 'as' => 'flow-type-index']);
    Route::match(['get', 'post'],'createtype', ['uses' => 'FlowController@createType', 'as' => 'flow-create-type']);
    Route::post('deltype', ['uses' => 'FlowController@delType', 'as' => 'flow-type-del']);
});
