<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeriesHasGenre extends Model
{
    protected $table = 'series_has_genre';
    protected $fillable = ['series_id', 'genre_id'];
    public $timestamps = false;
}
