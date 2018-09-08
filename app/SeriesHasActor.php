<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeriesHasActor extends Model
{
    protected $table = 'series_has_actor';
    protected $fillable = ['series_id', 'actor_id'];
    public $timestamps = false;

    public function actor()
    {
        return $this->belongsTo('App\Actor', 'actor_id');
    }
}
