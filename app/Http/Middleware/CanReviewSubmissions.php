<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanReviewSubmissions
{
    /**
     * Deja pasar solo al comité científico y a los administradores.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->canReviewSubmissions()) {
            abort(403, 'No tienes permiso para entrar aquí.');
        }

        return $next($request);
    }
}
