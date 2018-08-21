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

Route::get('/auth/facebook', 'SocialAuthFacebookController@redirect');
Route::get('/auth/facebook/callback', 'SocialAuthFacebookController@callback');
Route::get('/redirect', 'SocialAuthGoogleController@redirect');
Route::get('/callback', 'SocialAuthGoogleController@callback');

Route::get('/places', 'PlacesController@index');
Route::get('/dashboard', 'MapController@index')->middleware('authenticated');