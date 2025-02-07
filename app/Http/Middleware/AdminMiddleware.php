<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (! $user || $user->role?->name !== 'admin') {
            abort(Response::HTTP_UNAUTHORIZED, 'Unauthorized access');
        }

        return $next($request);
    }
}
