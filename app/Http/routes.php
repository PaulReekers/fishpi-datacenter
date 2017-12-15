<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'PagesController@home');
Route::get('/how-to', 'PagesController@howTo');


Route::get('/register', function () {

    if (Auth::user()) {
        return view('auth.register');
    } else {
        return view('auth.login');
    }

});

Route::post('/webhook', 'BotController@receive');
Route::get('/webhook', 'BotController@check');

Route::group(['middleware' => 'auth'], function () {
    Route::resource('bots', 'BotsController');
    Route::resource('bots/{id}/qna', 'QuestionsAndAnswerController');
});

Route::auth();

Route::get('/admin', 'AdminController@index');
Route::post('/admin', array('uses' => 'AdminController@store'));

Route::get('/admin/commands', 'AdminController@commands');


Route::post('api/v1/question','MessageController@storeQuestion');
Route::post('api/v1/question/{id}','MessageController@storeQuestion');
Route::post('api/v1/question/{id}/option','MessageController@storeOptionQuestion');
Route::post('api/v1/question/{id}/option/{option}','MessageController@storeOptionQuestion');

Route::get('api/v1/question','MessageController@getQuestion');
Route::get('api/v1/question/{id}','MessageController@getQuestion');


Route::get('api/v1/data','FishDataController@index');
Route::get('api/v1/data/store','FishDataController@store');
Route::get('api/v1/data/current','FishDataController@current');
Route::get('api/v1/data/{timestamp}','FishDataController@show');
Route::get('api/v1/current','FishDataController@current');
Route::get('api/v1/collection','FishDataController@collection');
Route::get('api/v1/command','CommandController@index');
Route::get('api/v1/command/ip','CommandController@getip');

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
