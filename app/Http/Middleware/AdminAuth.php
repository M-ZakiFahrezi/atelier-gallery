<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('admin')) {
            return redirect()->route('login')
                ->withErrors(['loginError' => 'Silakan login sebagai admin terlebih dahulu.']);
        }

        return $next($request);
    }
}
