<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// MAIN ENDPOINTS
Route::get('/', 'IndexController@index');
Route::get('/update', 'IndexController@update');

// LOGGED IN
Route::get('/timeline', 'IndexController@timeline');
Route::get('/notifications', 'IndexController@notifications');
Route::get('/profile', 'IndexController@profile');

// PLAYGROUND ENDPOINTS
Route::get('/playground', 'PlaygroundController@playground');
