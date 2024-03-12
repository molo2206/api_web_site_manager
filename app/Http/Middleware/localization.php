<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $local = ($request->hasHeader("Accept-Language")) ? $request->header("Accept-Language") : "en";     // set laravel localization
        app()->setLocale($local);    // continue request
        return $next($request);
    }
}
