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

// Default page -> Home
Route::get('/', function () {
    return view('home');
});

// Automatically adds Auth routes
Auth::routes();

// Home page with Postal Form
Route::get('/home', 'HomeController@index');

// Welcome page (Don't need)
Route::get('/welcome', function(){
    return view('welcome');
});

// Post for retrieving Geo Location
Route::post('/geo', 'HomeController@getLocation');

// Restaurants in proximity
Route::get('/restaurants', 'RestaurantController@index');

// User's restaurants
Route::get('/my_locations', 'RestaurantController@myLocations')->middleware('auth');


// RESTAURANT ROUTES

// Adding a Restaurant
Route::get('/add_restaurant', function(){
    return view('add_restaurant'); })->middleware('auth');
Route::put('/add_restaurant', 'RestaurantController@store')->middleware('auth');

// Details of a Restaurant
Route::get('/details/{resto_id}', 'RestaurantController@details');

// Deleting a restaurant
Route::delete('/delete/{resto_id}', 'RestaurantController@delete')->middleware('auth');

//Editing a restaurant
Route::get('/edit/{resto_id}', 'RestaurantController@edit')->middleware('auth');
Route::put('/edit_restaurant/{resto_id}', 'RestaurantController@update')->middleware('auth');


// FAVOURITE ROUTES

// Add a favourite
Route::put('/favourite_restaurant/{resto_id}', 'FavouriteController@store')->middleware('auth');

// Remove a favourite
Route::delete('/unfavourite_restaurant/{resto_id}', 'FavouriteController@delete')->middleware('auth');

// Display list of users favourite restaurants
Route::get('/my_favourites', 'RestaurantController@myFavourites')->middleware('auth');

// SEARCH
Route::get('/search', 'RestaurantController@search');

//Add a review
Route::put('/review/{resto_id}', 'ReviewController@store')->middleware('auth');
Route::delete('/review/{review_id}', 'ReviewController@delete')->middleware('auth');