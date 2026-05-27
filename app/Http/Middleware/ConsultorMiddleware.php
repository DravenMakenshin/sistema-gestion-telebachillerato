<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || (!Auth::user()->isAdmin() && !Auth::user()->isConsultor())) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        return $next($request);
    }
}