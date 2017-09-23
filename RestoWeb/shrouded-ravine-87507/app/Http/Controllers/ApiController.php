<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Restaurant;
use App\Review;
use App\Repositories\RestaurantRepository;
use Auth;

class ApiController extends Controller
{
    /**
     * The Restaurant repository instance.
     *
     * @var TaskRepository
     */
    protected $restos;
    
    /**
     * Create a new controller instance.
     *
     * @param  RestaurantRepository $restos
     * @return void
     */
    public function __construct(RestaurantRepository $restos)
    {
        $this->restos = $restos;
    }
    
    /**
     * Retrieves the reviews for the restaurant given and sends a JSON response with those reviews.
     * 
     * @param Request $request
     * @param type $id Id of the restaurant to be retrieved
     * @return type JSON response with the reviews.
     */
    public function reviews(Request $request, $id)
    {
        $reviews = Restaurant::find($id)->reviews;
        
        return response()->json($reviews, 200);
    }
    
    /**
     * Retrieves the 10 closest restaurants given the user's latitude and longitude.
     * 
     * @param Request $request
     * @param type $latitude User's latitude.
     * @param type $longitude User's longitude.
     * @return type JSON response with 10 nearest restaurants.
     */
    public function nearestRestos(Request $request, $latitude, $longitude)
    {
        $restos = $this->restos->getRestosNear($latitude, $longitude);
        
        //LIMIT IT TO 10 RESTOS
        
        
        return response()->json($restos, 200);       
    }
    
    /**
     * If the user is successfully authenticated, this method will add a new restaurant 
     * to the database given all the information from a POST request. 
     * 
     * @param Request $request
     * @return type
     */
    public function addResto(Request $request)
    {
        //grab credentials from the request
        $credentials = $request->only('email', 'password');  //Only Some Of The Request Input
        $valid = Auth::once($credentials); //logs in for this time only, no session or cookies
        if (!$valid){
            return response()->json(['error' => 'invalid_credentials'], 401);
        }else {
            $this->validate($request, [
                'name' => 'required|max:255',
                'notes' => 'required|max:255',
                'priceRange' => 'required|integer|min:1|max:4',
                'genre' => 'required|max:255',
                'image' => 'max:255',
                'address' => 'required|max:255',
                'city' => 'required|max:255',
                'postalCode' => 'required|max:255',
            ]);

            //If the restaurant and postal code combination already exists a 400 response will be sent.
            if ((Restaurant::where('name', '=', $request->name)->count() > 0) && (Restaurant::where('postalCode', '=', $request->postalCode)->count() > 0)){
                return response()->json(400);
            }
            
            // Get the latitude and longitude of the address
            $geo = $this->restos->GetGeocodingSearchResults($request->address.', '.$request->city);

            // Create the restaurant
            Restaurant::create([
                'name' => $request->name,
                'notes' => $request->notes,
                'priceRange' => $request->priceRange,
                'genre' => $request->genre,
                'address' => $request->address,
                'city' => $request->city,
                'postalCode' => $request->postalCode,
                'image' => $request->image,
                'latitude' => $geo['latitude'],
                'longitude' => $geo['longitude'],
                'user_id' => $request->user()->user_id,
            ]);

            return response()->json(201);
       }
    }
    
    /**
     * If the user is successfully authenticated, this method will add a new review  
     * for a restaurant to the database given all the information from a POST request. 
     * 
     * @param Request $request
     * @return type
     */
    public function addReview(Request $request)
    {
        //get credentials from the request and authenticate user
        $credentials = $request->only('email', 'password');
        $valid = Auth::once($credentials);
        if (!$valid){
            return response()->json(['error' => 'invalid_credentials'], 401);
        }else {
            $this->validate($request, [
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|max:255',
                'title' => 'required|max:255',
                'resto_id' => 'required|max:255',
            ]);

            // Create the review
            Review::create([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'title' => $request->title,
                'resto_id' => $request->resto_id,
                'user_id' => $request->user()->user_id,
            ]);
            
            return response()->json(201);
        }
    }

}