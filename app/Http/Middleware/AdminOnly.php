<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $key = $request->header('api-key');
        if (!$key) {
            abort(403);
        }

        $user = User::where('api_key', $key)->first();
        if ($user->role !== 'admin') {
            abort(403);
        }

        $request->user = $user;
        return $next($request);
    }
}
