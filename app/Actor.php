<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    protected $guarded = ['id'];

    public function movies()
    {
        return $this->belongsToMany('App\Movie', 'movie_has_actor', 'actor_id', 'movie_id');
    }
}
