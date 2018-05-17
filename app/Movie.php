<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    public function rentedBy()
    {
        return $this->belongsToMany('App\User', 'rental', 'movie_id', 'user_id');
    }

    public function actors()
    {
        return $this->belongsToMany('App\Actor', 'movie_has_actor', 'movie_id', 'actor_id');
    }
}
