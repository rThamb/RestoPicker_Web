<?php

namespace App\Http\Controllers;

use DB;
use App\Review;
use App\Restaurant;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\RestaurantRepository;

class RestaurantController extends Controller
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
     * @param  RestaurantRepository  $tasks
     * @return void
     */
    public function __construct(RestaurantRepository $restos)
    {
        //$this->middleware('auth');
        $this->restos = $restos;
    }
    
    /**
     * Returns a view of the closest restaurants, based on
     * a provided location or longitude and latitude)
     * @param Request $request
     * @return view
     */
    public function index(Request $request){
        // Get session
        $session = $request->session();
        
        $restoArray = array();
        
        // Check the longitude/latitude or PostalCode are set
        if($session->has('latitude') && $session->has('longitude')){
            // Retrieve closest restaurants to the lat and long
            $restoArray = $this->restos->getRestosNear($session->get('latitude'),
                    $session->get('longitude'), 50);
        }
        else if($session->has('postal')){
            $coords = $this->restos->GetGeocodingSearchResults($session->get('postal'));
            if($coords['status'] == "OK"){
                $restoArray = $this->restos->getRestosNear($coords['latitude'],
                        $coords['longitude'], 50);
            }
        }
        else {
            return view('home');
        }
        
        // Use private method for displaying restos properly
        return $this->displayRestos($restoArray, 'Closest Restaurants', 20);
    }
    
    /**
     * Returns a view of the restaurants that the user has added.
     * @param Request $request
     * @return view
     */
    public function myLocations(Request $request){
        $user = $request->user();
        $restos = $user->restaurants;
        
        // Use private method for displaying restos properly
        return $this->displayRestos($restos, 'My Locations', 20);
    }
    
    /**
     * Returns a view of the users favourite restaurants.
     * @param Request $request
     * @return view
     */
    public function myFavourites(Request $request){
        $user = $request->user();
        $favs = $user->favourites;
        
        $restos = array();
        foreach($favs as $fav)
            $restos[] = $fav->restaurant;
        
        // Use private method for displaying restos properly
        return $this->displayRestos($restos, 'My Favourite Restaurants', 20);
    }
    
    /**
     * Private method for displaying a view of the Restaurants
     * in a paginated matter.
     * 
     * @param $restoArray
     * @return view
     */
    private function displayRestos($restoArray, $title = "Results", $itemsPerPage = 2){
        $restoIds = array(); $restoRatings = array(); $restoDistances = array();
        foreach($restoArray as $resto){
            // Retrieve Resto id
            $restoIds[] = $resto->resto_id;
            
            // Retrieve average rating
            $restoRatings[] = DB::table('restaurants')->join('reviews',
                    'reviews.resto_id', '=', 'restaurants.resto_id')
                    ->where('restaurants.resto_id', $resto->resto_id)
                    ->select(DB::raw('COUNT(review_id) AS num'), DB::raw('AVG(rating) AS rating'))->get()[0];
            
            // If distane information is included (Closest Restos)
            if(isset($resto->distance))
                $restoDistances[] = $resto->distance;
        }

        // Create Case for ordering the Resto Ids in the Query
        $restoCase = '(CASE ';
        for($i = 0; $i < count($restoIds); $i++){
            $restoCase = $restoCase.'when resto_id = '.$restoIds[$i].' then '.$i.' ';
        }
        $restoCase = $restoCase.'end)';
        
        // Retrieve complete restaurant information, order it how it came, and paginate
        $restaurants = Restaurant::whereIn('resto_id', $restoIds)
                ->orderByRaw(DB::raw($restoCase))
                ->paginate($itemsPerPage);
        
        return view('restaurants')->with('restaurants', $restaurants)
                ->with('ratings', $restoRatings)
                ->with('distances', $restoDistances)->with('title', $title);
        
    }
    
    /**
     * Returns a view displaying the results of a search.
     * @param Request $request
     * @return view
     */
    public function search(Request $request){
        $restaurants = Restaurant::where('name', 'like', '%'.$request->searchKey.'%')
                ->orWhere('genre', 'like', '%'.$request->searchKey.'%')
                ->orWhere('city', 'like', '%'.$request->searchKey.'%')->get();
        
        return $this->displayRestos($restaurants, 'Search Results', 10);
    }
    
    /*
     * Creates a new Restaurant with the signed in User
     * 
     * @param $request Request
     * @return redirect
     */
    public function store(Request $request){
        // Validate the data
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
        
        return redirect('/home');
        
    }
    
    /**
     * Returns the details of a Restaurant
     * 
     * @param Request $request
     * @param int $id
     * @return view
     */
    public function details(Request $request, $id){
        $restaurant = Restaurant::find($id);
        
        // Retrieve average rating
        $rating = DB::table('restaurants')->join('reviews',
                'reviews.resto_id', '=', 'restaurants.resto_id')
                ->where('restaurants.resto_id', $restaurant->resto_id)
                ->select(DB::raw('COUNT(review_id) AS num'),
                        DB::raw('AVG(rating) AS rating'))->get()[0];
        
        // Retrieve reviews in paginated format
        $reviews = Review::where('resto_id', '=', $id)->orderBy('created_at', 'DESC')->paginate(5);
        
        return view('details')->with('restaurant', $restaurant)
                ->with('restoRating', $rating)->with('reviews', $reviews);
    }
    
    /**
     * Deletes the selected Restaurant, if the User owns it.
     * 
     * @param Request $request
     * @param int $id
     * @return redirect
     */
    public function delete(Request $request, $id){
        $restaurant = Restaurant::find($id);
        
        if($restaurant->user == $request->user()){
            Restaurant::destroy($restaurant->resto_id);
            return redirect()->back();
        }
        
        return redirect()->back();
    }
    
    /**
     * Show the form for editing the specified restaurant.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        // get the restaurant
        $restaurant = Restaurant::find($id);

        // show the edit form and pass the nerd
        return view('edit_restaurant')
            ->with('restaurant', $restaurant);
    }
    
    /**
     * Update the specified restaurant in the database.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
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
        
        // Get the latitude and longitude of the address
        $geo = $this->restos->GetGeocodingSearchResults($request->address.', '.$request->city);
        
        // store
        $restaurant = Restaurant::find($id);
        $restaurant->name = $request->name;
        $restaurant->notes = $request->notes;
        $restaurant->priceRange = $request->priceRange;
        $restaurant->genre = $request->genre;
        $restaurant->address = $request->address;
        $restaurant->city = $request->city;
        $restaurant->image = $request->image;
        $restaurant->postalCode = $request->postalCode;
        $restaurant->latitude = $geo['latitude'];
        $restaurant->longitude = $geo['longitude'];
        $restaurant->save();

        return view('details')->with('restaurant', $restaurant);
    }
    
}