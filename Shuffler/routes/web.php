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

Route::get('/','PagesController@index'); 
Route::get('/about', 'PagesController@about');

Auth::routes();

Route::get('/search', 'SearchesController@index')->middleware('authenticated');
Route::post("search", 'SearchesController@store')->middleware('authenticated');
Route::get('/allplaces', 'AllPlacesController@index')->middleware('authenticated');
Route::get('/shuffle', 'ShuffleController@index')->middleware('authenticated');

Route::get('/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');