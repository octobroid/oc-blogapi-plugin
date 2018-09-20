<?php

Route::group([
    'domain' => env('API_DOMAIN'),
    'prefix' => env('API_PREFIX', 'api') .'/v1' . '/blog',
    'namespace' => 'Octobro\BlogAPI\APIControllers',
    'middleware' => 'cors',
], function() {

    Route::get('posts','Posts@index');
    Route::get('posts/{id}','Posts@show');
    Route::get('categories','Categories@index');
    Route::get('categories/{id}','Categories@show');

});
