<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    public $timestamps = true;
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
