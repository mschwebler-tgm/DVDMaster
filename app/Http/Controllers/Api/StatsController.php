<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        $movieCount = DB::table('movies')
            ->select(DB::raw('COUNT(*) as amount'))
            ->whereNull('deleted_at')
            ->where('type', '=', 'movie')
            ->first()->amount;
        $newMoviesCount = DB::table('movies')
            ->select(DB::raw('COUNT(*) as amount'))
            ->whereNull('deleted_at')
            ->where('type', '=', 'movie')
            ->where('created_at', '>=', Carbon::now()->subWeek())
            ->first()->amount;
        $seriesCount = DB::table('movies')
            ->select(DB::raw('COUNT(*) as amount'))
            ->whereNull('deleted_at')
            ->where('type', '=', 'series')
            ->first()->amount;
        $pendingRentalsCount = DB::table('rentals')
            ->select(DB::raw('COUNT(*) as amount'))
            ->where('state', '=', 'pending')
            ->first()->amount;
        $oldRentals = DB::table('rentals')
            ->select(DB::raw('COUNT(*) as amount'))
            ->where('state', '=', 'pending')
            ->where('created_at', '<=', Carbon::now()->subMonth())
            ->first()->amount;

        return compact(['movieCount', 'seriesCount', 'pendingRentalsCount', 'newMoviesCount', 'oldRentals']);
    }
}