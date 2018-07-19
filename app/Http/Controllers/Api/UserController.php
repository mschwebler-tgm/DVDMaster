<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getAllExcept($id)
    {
        return User::where('id', '!=', $id)->get();
    }
}