<?php

Route::group(['prefix' => 'flow', 'namespace' => 'WuTongWan\Flow\Http\Controllers'], function () {

    Route::get('/', ['uses' => 'FlowController@index', 'as' => 'flow-index']);
    Route::get('index', ['uses' => 'FlowController@index', 'as' => 'flow-index']);
    Route::match(['get', 'post'],'createflow', ['uses' => 'FlowController@createFlow', 'as' => 'flow-create']);
    Route::post('delflow', ['uses' => 'FlowController@delFlow', 'as' => 'flow-del']);

    Route::get('type', ['uses' => 'FlowController@getType', 'as' => 'flow-type-index']);
    Route::match(['get', 'post'],'createtype', ['uses' => 'FlowController@createType', 'as' => 'flow-create-type']);
    Route::post('deltype', ['uses' => 'FlowController@delType', 'as' => 'flow-type-del']);

    Route::get('node', ['uses' => 'FlowController@getNode', 'as' => 'flow-node-index']);
    Route::match(['get', 'post'],'createnode', ['uses' => 'FlowController@createNode', 'as' => 'flow-create-node']);
});
