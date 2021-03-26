<?php

namespace App\Http\Middleware;

use Closure;

class GeneralTokenProtection
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->token == env('GENERAL_SECRET', 'secret-123456789-abcdefgh'))
        {
            return $next($request);
        }

        return redirect('/');
    }
}
