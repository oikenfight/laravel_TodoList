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

//Route::resource('todos', 'TodosController');



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

Route::group(['middleware' => ['web']], function () {
    Route::group(['prefix' => 'todos'], function() {
        Route::get('', [
            'as' => 'todos.index',
            'uses' => 'TodosController@index',
        ]);

        Route::post('', [
            'as' => 'todos.store',
            'uses' => 'TodosController@store',
        ]);

        Route::post('{id}/update', [
            'as' => 'todos.update',
            'uses' => 'TodosController@update',
        ]);

        Route::post('{id}/delete', [
            'as' => 'todos.delete',
            'uses' => 'TodosController@delete',
        ]);

        Route::post('{id}/restore', [
            'as' => 'todos.restore',
            'uses' => 'TodosController@restore',
        ]);
    });
});

Route::group(['middleware' => ['api']], function () {
    Route::group(['prefix' => 'todos'], function () {
        Route::put('{id}/title', [
            'as' => 'todos.update-title',
            'uses' => 'TodosController@ajaxUpdateTitle',
        ]);
    });
});
