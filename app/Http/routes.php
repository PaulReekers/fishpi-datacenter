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

Route::auth();

Route::get('/admin', 'AdminController@index');
Route::post('/admin', array('uses' => 'AdminController@store'));

Route::get('/admin/commands', 'AdminController@commands');

Route::get('api/v1/data','FishDataController@index');
Route::get('api/v1/data/store','FishDataController@store');
Route::get('api/v1/data/current','FishDataController@current');
Route::get('api/v1/data/{timestamp}','FishDataController@show');
Route::get('api/v1/drawgauge','FishDataController@drawGauge');
Route::get('api/v1/drawlinechart','FishDataController@drawLineChart');
Route::get('api/v1/command','CommandController@index');
