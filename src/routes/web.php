<?php
/*
|--------------------------------------------------------------------------
| Ignicms Web Routes
|--------------------------------------------------------------------------
*/

// Admin
Route::group(['prefix' => 'admin'], function () {
    // Authentication routes...
    // Route::get('login', 'Auth\AuthController@getLogin');
    // Route::post('login', 'Auth\AuthController@postLogin');
    // Route::post('logout', 'Auth\AuthController@getLogout');

    Auth::routes();

    Route::group(['middleware' => 'auth.admin'], function () {
        Route::get('/', ['as' => 'adminHome', 'uses' => 'Admin\DefaultController@adminHome']);
        Route::get('/403', ['as' => 'adminForbidden', 'uses' => 'Admin\DefaultController@forbidden']);

        //        Route::resource('user', 'UsersController',
        //            [
        //                'names' => build_resource_backport('user'),
        //            ]
        //        );

        //        Route::post('file/{file}', 'FileController@get')->name('file.get');
        //        Route::match(['get', 'post'], 'image/upload', 'Admin\ImageController@upload')->name('image.upload');
        //        Route::get('image/preview/{temp_image?}', 'Admin\ImageController@preview')->name('image.preview');
    });
});
