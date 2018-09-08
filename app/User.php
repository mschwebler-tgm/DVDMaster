<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token', 'api_key'];

    public function borrowedMovies()
    {
        $this->belongsToMany('App\Movie', 'rental', 'user_id', 'movie_id');
    }
}
