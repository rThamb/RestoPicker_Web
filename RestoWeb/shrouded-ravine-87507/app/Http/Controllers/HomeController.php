<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    /**
     * Returns the user a view of the closest restaurants,
     * after determing the longitude and latitude with their location.
     * @param Request $request
     * @return 
     */
    public function getLocation(Request $request){
        // Get the session
        $session = $request->session();
        
        if($request->error == 0){
            $session->put('latitude', $request->latitude);
            $session->put('longitude', $request->longitude);
            return redirect()->action('RestaurantController@index');
        }
        else if($request->postal !== ''){
            $session->put('postal', $request->postal);
            return redirect()->action('RestaurantController@index');
        }
        else{
            echo $request->error;
            return view('home');
        }
    }
}