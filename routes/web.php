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

// PLAYGROUND ENDPOINTS
Route::get('/playground', 'PlaygroundController@playground');
Route::get('/reddit', 'PlaygroundController@reddit');
