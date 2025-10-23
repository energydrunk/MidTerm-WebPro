<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSeller
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('info', 'Please login first.');
        }

        if (strtolower(auth()->user()->email) !== 'adminbakery@gmail.com') {
            return redirect()->route('home')->with('info', 'Seller access only.');
        }

        return $next($request);
    }
}