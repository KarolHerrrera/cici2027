<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
   public function handle(Request $request, Closure $next): Response
{
    if (! $request->user() || ! $request->user()->is_admin) {
        abort(403, 'No tienes permiso para entrar aquí.');
    }

    return $next($request);
}
}
