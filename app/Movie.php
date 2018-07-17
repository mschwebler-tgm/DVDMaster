<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function rentedBy()
    {
        return $this->belongsToMany('App\User', 'rentals', 'movie_id', 'user_id');
    }

    public function actors()
    {
        return $this->belongsToMany('App\Actor', 'movie_has_actor', 'movie_id', 'actor_id');
    }

    public function genres()
    {
        return $this->belongsToMany('App\Genre', 'movie_has_genre', 'movie_id', 'genre_id');

    }
}
