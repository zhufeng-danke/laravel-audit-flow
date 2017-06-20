<?php

Route::group(['prefix' => 'flow'], function () {

    Route::get('/', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@index', 'as' => 'flow-index']);
    Route::get('index', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@index', 'as' => 'flow-index']);
    Route::match(['get', 'post'],'createflow', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@createFlow', 'as' => 'flow-create']);
    Route::post('delflow', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@delFlow', 'as' => 'flow-del']);

    Route::get('type', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@getType', 'as' => 'flow-type-index']);
    Route::match(['get', 'post'],'createtype', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@createType', 'as' => 'flow-create-type']);
    Route::post('deltype', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@delType', 'as' => 'flow-type-del']);

    Route::get('node', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@getNode', 'as' => 'flow-node-index']);
    Route::match(['get', 'post'],'createnode', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@createNode', 'as' => 'flow-create-node']);

    Route::match(['get', 'post'],'createuser', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@createUser', 'as' => 'flow-create-user']);
    Route::post('deluser', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@delUser', 'as' => 'flow-user-del']);

    Route::get('bill', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@getBill', 'as' => 'flow-bill-index']);
    Route::get('show_node', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@getBillNode', 'as' => 'flow-bill-node']);
    Route::post('bill_close', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@billClose', 'as' => 'flow-bill-close']);
    Route::get('records', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@getRecords', 'as' => 'flow-records-index']);

    Route::get('create_relations', ['uses' => '\WuTongWan\Flow\Http\Controllers\FlowController@createBillFlowRelations', 'as' => 'create-bill-flow-relations']);
});
