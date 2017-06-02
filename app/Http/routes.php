<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
//
//Route::get('index', function () {
//    return view('index');
//});
//
//Route::get('index2', function () {
//    return view('index2');
//});
//
//Route::get('show', function () {
//    echo ("1111111111");
//});
//
//Route::any('test', ['uses' => 'ImageController@test']);
//
//Route::any('getData', ['uses' => 'ImageController@getData']);
//Route::any('delData', ['uses' => 'ImageController@delData']);
//
//
//
//Route::any('labelimage', ['uses' => 'ImageController@index']);
//Route::any('init', ['uses' => 'ImageController@init']);
/*


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['cors']], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('index', function () {
        return view('index');
    });

    Route::get('index2', function () {
        return view('index2');
    });


    Route::get('show', function () {
        echo ("1111111111");
    });

    Route::any('test', ['uses' => 'ImageController@test']);

    Route::any('getData', ['uses' => 'ImageController@getData']);
    Route::any('delData', ['uses' => 'ImageController@delData']);



    Route::any('labelimage', ['uses' => 'ImageController@index']);
    Route::any('init', ['uses' => 'ImageController@init']);
});

Route::auth();

Route::get('/home', 'HomeController@index');
