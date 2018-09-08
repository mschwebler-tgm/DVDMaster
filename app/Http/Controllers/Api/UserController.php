<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
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

    public function create(Request $request)
    {
        $name = $request->get('name');
        if (!$name) { abort(404); }

        $existingUser = User::where('name', $name)->first();
        if ($existingUser) { return $existingUser; }

        $user = new User();
        $user->name = $name;
        $user->email = "$name@$name.com";
        $user->password = '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'; // secret
        $user->api_key = str_random(32);
        $user->save();
        return $user;
    }
}