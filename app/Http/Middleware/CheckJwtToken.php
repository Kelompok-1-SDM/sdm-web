<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class CheckJwtToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Check if the token exists and has not expired
        if (Cache::has('api_jwt_token')) {
            return Redirect::route('home'); // Adjust to your home route
        }

        return $next($request);
    }
}
