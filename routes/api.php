<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route:: get('/allStylists', [
    'uses' =>'StylistController@getAllStylists'
]);

Route::post('/addStylist', [
    'uses' =>'StylistController@addStylist'
]);

Route::get('/searchStylist/{firstname}/{lastname}', [
    'uses' =>'StylistController@searchStylist'
]);

Route::get('/searchStylist/{keyname}', [
    'uses' =>'StylistController@searchStylist2'
]);

Route::get('/getRates', [
    'uses' =>'StylistController@getRates'
]);
Route::get('/getSkills', [
    'uses' =>'StylistController@getSkills'
]);

Route::get('/getJobTypes', [
    'uses' =>'StylistController@getJobTypes'
]);

Route::get('/getLocations', [
    'uses' =>'StylistController@getLocations'
]);

Route::post('/filter', [
    'uses' =>'StylistController@filter'
]);

Route::post('/profile/{id}', [
    'uses' => 'StylistController@viewProfile'
]);

Route::post('/gallery/{id}', [
    'uses' => 'StylistController@viewGallery'
]);

Route::post('/schedule/{id}', [
    'uses' => 'StylistController@viewSchedule'
]);
