<?php

namespace App\Http\Controllers;

use App\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Stores a review created by the current user.
     * 
     * @param Request $request
     * @param int $id
     * @return redirect
     */
    public function store(Request $request, $id){
        // Validate the data
        $this->validate($request, [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|max:255',
            'title' => 'required|max:255',
        ]);
        
        // Create the review
        Review::create([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'title' => $request->title,
            'resto_id' => $id,
            'user_id' => $request->user()->user_id,
        ]);
        
        return redirect()->back();
        
    } 
    
    /**
     * Deletes a review for a Restaurant
     * 
     * @param Request $request
     * @param int $id
     * @return redirect
     */
    public function delete(Request $request, $id){
        $review = Review::find($id);
        
        if(!empty($review))
            Review::destroy($id);
        
        return redirect()->back();
    }
    
}