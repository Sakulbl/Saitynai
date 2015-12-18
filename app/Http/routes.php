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

Route::resource('create', 'UsersController');

Route::resource('login', 'MainController');

Route::resource('overview', 'OverviewController');

Route::resource('hunting', 'HuntingController');

Route::resource('inventions', 'InventionsController');

Route::resource('exploring', 'ExploringController');

Route::resource('sleeping', 'SleepingController');

Route::resource('fighting', 'FightingController');

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('login', 'MainController@postLogin');
Route::get('logout', 'Auth\AuthController@getLogout');

// Hunting route
Route::post('hunt', 'HuntingController@postHunt');

// Inventing route
Route::post('invent', 'InventionsController@postInvent');

// Exploring route
Route::post('explore', 'ExploringController@postExplore');

// Sleeping route
Route::post('sleep', 'SleepingController@postSleep');

// Fighting route
Route::get('fight/{name}', 'FightingController@getFight');

Route::get('/', function(){
	return Redirect::to('login');
});

//-------------------------API ROUTES----------------------------
Route::get('api/getChar', 'JsonController@getCharData');

Route::put('api/updateChar', 'JsonController@updateCharData');