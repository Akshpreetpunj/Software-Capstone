<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if(Auth::check()){
                if(auth()->user()->is_admin == 1){
                    return $next($request);
                } 
            } else {
                return redirect('login')->with('error', "Please login with admin access account.");
            }
        } catch (\Exception $exception) {
            return redirect('home')->with('error', "You don't have admin access.");
        }
    }
}
