<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $primaryKey = 'review_id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rating', 'comment', 'title', 'resto_id', 'user_id'
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
        return $this->belongsTo(Restaurant::class);
    }
    
}
