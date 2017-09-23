<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'firstname', 'lastname', 'username', 'password', 'postalCode'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * Get all the reviews for this restaurant.
     */
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }
    
    /**
     * Get all the reviews for this restaurant.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    /*
     * Get all the users favourite restaurants.
     */
    public function favourites()
    {
        return $this->hasMany(Favourite::class);
    }
}
