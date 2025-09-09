<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;  
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is not authenticated or if userType is not '1'
        if (!Auth::check() || !in_array(Auth::user()->userType, ['0', '1', '2'])) {
            return redirect('/');
        }
        

        return $next($request); 
    }
}
