<?php

Route::group(['prefix' => 'flow', 'namespace' => 'WuTongWan\Flow\Http\Controllers'], function () {

    Route::get('index', ['uses' => 'FlowController@index', 'as' => 'flow-index']);

});
