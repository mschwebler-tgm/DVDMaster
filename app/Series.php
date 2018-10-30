<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Series extends Model
{
    use SoftDeletes;

    protected $casts = [
        'based_on_book' => 'boolean',
        'blue_ray' => 'boolean',
        'belongs_to_collection' => 'boolean',
        'true_story' => 'boolean',
    ];

    public function rentedBy()
    {
        return $this->belongsToMany('App\User', 'rentals', 'series_id', 'user_id')->whereNull('retrieved_at');
    }

    public function actors()
    {
        return $this->belongsToMany('App\Actor', 'series_has_actor', 'series_id', 'actor_id')->orderBy('popularity', 'desc');
    }

    public function genres()
    {
        return $this->belongsToMany('App\Genre', 'series_has_genre', 'series_id', 'genre_id');
    }

    public function rentals()
    {
        return $this->hasMany('App\Rental');
    }

    public function pendingRental()
    {
        return $this->hasMany('App\Rental')->where('state', 'pending');
    }

    public function seasons()
    {
        return $this->hasMany('\App\Season');
    }
}
