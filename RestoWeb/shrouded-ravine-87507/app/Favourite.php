<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $primaryKey = 'fav_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'resto_id'
    ];
    
    /**
     * Get the user that favourited the restaurant.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the user that created the restaurant.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'resto_id');
    }
    
}
