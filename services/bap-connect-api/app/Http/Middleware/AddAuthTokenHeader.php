<?php

namespace App\Http\Middleware;

use App\Traits\HandleRequestMiddleware;
use Closure;
use Illuminate\Http\Request;

class AddAuthTokenHeader
{
    use HandleRequestMiddleware;

    /**
     * Handle an incoming request.
     *
     * @param  $request  Request
     * @param  Closure  $next  Closure
     * @param  ...$guards  guards
     *
     * @return Closure Closure
     */
    public function handle(Request $request, Closure $next, ...$guards): mixed
    {
        $request = $this->injectAuthTokenFromCookie($request);

        return $next($request);
    }
}
