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
Route::group(['namespace' => 'Api'], function () {
    Route::get('/', 'FoursquareController@getCategories')->name('foursquare.categories');
    Route::post('/get-info', 'FoursquareController@getInfo')->name('foursquare.getInfo');
});