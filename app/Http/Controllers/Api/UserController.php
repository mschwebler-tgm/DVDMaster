<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getAllExcept($id)
    {
        if (!$id || $id === 'null') {
            abort(404);
        }
        return User::where('id', '!=', $id)->get();
    }
}