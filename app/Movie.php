<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $casts = [
        'based_on_book' => 'boolean',
        'blue_ray' => 'boolean',
        'belongs_to_collection' => 'boolean',
        'true_story' => 'boolean',
    ];

    public function rentedBy()
    {
        return $this->belongsToMany('App\User', 'rentals', 'movie_id', 'user_id');
    }

    public function actors()
    {
        return $this->belongsToMany('App\Actor', 'movie_has_actor', 'movie_id', 'actor_id')->orderBy('popularity', 'desc');
    }

    public function genres()
    {
        return $this->belongsToMany('App\Genre', 'movie_has_genre', 'movie_id', 'genre_id');
    }

    public function rentals()
    {
        return $this->hasMany('App\Rental');
    }

    public function pendingRental()
    {
        return $this->hasMany('App\Rental')->where('state', 'pending');
    }
}
