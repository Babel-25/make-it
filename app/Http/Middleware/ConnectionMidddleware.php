<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ConnectionMidddleware
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
        if (auth()->guest()) {
            session()->flash('message', 'Vous devez être connecté !');
            return redirect()->route('login_form');
        }
        return $next($request);
    }
}
