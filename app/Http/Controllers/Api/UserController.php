<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    public function getAllExcept($id)
    {
        return User::where('id', '!=', $id)->get();
    }

    public function addFromName(Request $request)
    {
        $user = User::where('name', $request->get('name'))->first();
        if ($user) {
            abort(403, 'User already exists');
        }

        $user = new User();
        $user->name = $request->get('name');
        $user->password = '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm';  // secret
        $user->save();
        return $user;
    }
}