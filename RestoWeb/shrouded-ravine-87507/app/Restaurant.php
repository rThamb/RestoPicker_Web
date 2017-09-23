<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $primaryKey = 'resto_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'notes', 'priceRange', 'genre', 'address', 'image',
        'postalCode', 'latitude', 'longitude', 'user_id', 'city'
    ];
    
    /**
     * Get all the reviews for this restaurant.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'resto_id');
    }
    
    /**
     * Get the user that created the restaurant.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get favourites for the restaurant.
     */
    public function favourites(){
        return $this->hasMany(Favourite::class, 'resto_id');
    }
}
