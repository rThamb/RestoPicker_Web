<?php

namespace App\Http\Controllers;

use App\Favourite;
use App\Restaurant;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    /**
     * Favourites a restaurant for the signed in User.
     * 
     * @param Request $request
     * @param int $id
     * @return redirect
     */
    public function store(Request $request, $id){
        Favourite::create([
            'resto_id' => $id,
            'user_id' => $request->user()->user_id,
        ]);
        
        return redirect()->back();
    }
    
    /**
     * Unfavourites a restaurant for the signed in User.
     * 
     * @param Request $request
     * @param int $id
     * @return redirect
     */
    public function delete(Request $request, $id){
        // Find the Favourite
        $fav = Favourite::where([
            'resto_id' => $id,
            'user_id' => $request->user()->user_id,
        ])->first();
        
        // Destroy the favourite, if it exists
        if(!empty($fav))
            Favourite::destroy($fav->fav_id);
        
        return redirect()->back();
    }
    
    
}
