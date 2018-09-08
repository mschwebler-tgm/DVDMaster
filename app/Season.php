<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Season extends Model
{
    use SoftDeletes;

    public function series()
    {
        $this->belongsTo('App\Series', 'series_id');
    }
}
