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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

//Get all the reviews for a particular restaurant
Route::get('reviews/{resto_id}', 'ApiController@reviews');
//Get the 10 nearest restaurants using user's latitude, longitude
Route::get('restaurants/{latitude},{longitude}', 'ApiController@nearestRestos');
//Add a restaurant
Route::post('add_restaurant', 'ApiController@addResto');
//Add a review
Route::post('add_review', 'ApiController@addReview');
