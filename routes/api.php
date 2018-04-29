<?php

Route::group(['namespace' => 'Api'], function () {
    Route::any('yzy/create', 'YzyController@create');
    Route::resource('yzy', 'YzyController');

    Route::get('login', 'LoginController@login');
});

Route::group(['middleware' => ['mu'] ], function () {
    Route::get('mu/users', 'Mu\UserController@index');
    Route::post('mu/users/{id}/traffic', 'Mu\UserController@addTraffic');
    Route::post('mu/nodes/{id}/online_count', 'Mu\NodeController@onlineUserLog');
    Route::post('mu/nodes/{id}/info', 'Mu\NodeController@info');
});