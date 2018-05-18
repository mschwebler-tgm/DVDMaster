<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovieHasActor extends Model
{
    protected $table = 'movie_has_actor';
    protected $fillable = ['movie_id', 'actor_id'];
    public $timestamps = false;
}
