<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovieHasGenre extends Model
{
    protected $table = 'movie_has_genre';
    public $timestamps = false;
}
