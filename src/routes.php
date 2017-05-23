<?php

Route::group(['prefix' => 'flow', 'namespace' => 'WuTongWan\Flow\Http\Controllers'], function () {

    Route::get('/', ['uses' => 'FlowController@index', 'as' => 'flow-index']);
    Route::get('index', ['uses' => 'FlowController@index', 'as' => 'flow-index']);

});
