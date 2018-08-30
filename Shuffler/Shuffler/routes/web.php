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
Route::get('/services', 'PagesController@services');

Auth::routes();



Route::get('/places', 'PlacesController@index')->middleware('authenticated');
Route::get('/dashboard', 'MapController@index')->middleware('authenticated');

Route::get('/location', 'LocationController@index');

Route::get('/redirect', 'Auth\LoginController@redirectToProvider');
Route::get('/callback', 'Auth\LoginController@handleProviderCallback');