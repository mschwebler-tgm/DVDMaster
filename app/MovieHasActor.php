<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovieHasActor extends Model
{
    protected $table = 'movie_has_actor';
    public $timestamps = false;
}
