<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JollyController extends Controller
{
    public function index()
    {
        return view('external.jolly');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (!$data || !isset($data['images'])) {
            return '';
        }

        $images = $data['images'];
        $imagePaths = [];
        foreach ($images as $image) {
            $imagePaths[] = $image->store('jolly', 'public');
        }

        DB::table('jolly')->insert(['data' => json_encode($imagePaths)]);
        return $imagePaths;
    }

    public function images()
    {
        $data = DB::table('jolly')->select('data')->get();
        $paths = [];
        foreach ($data->pluck('data') as $datum) {
            $paths = array_merge($paths, json_decode($datum));
        }
        return $paths;
    }
}
