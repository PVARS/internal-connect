<?php

namespace App\Http\Middleware;

use App\Exceptions\AppException;
use App\Traits\HandleRequestMiddleware;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Override;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    use HandleRequestMiddleware;

    /**
     * Handle an incoming request.
     *
     * @param  $request  Request
     * @param  Closure  $next  Closure
     * @param  ...$guards  guards
     *
     * @throws AppException Unauthenticated
     *
     * @return Closure Closure
     */
    #[Override]
    public function handle($request, Closure $next, ...$guards): mixed
    {
        $request = $this->injectAuthTokenFromCookie($request);
        try {
            $this->authenticate($request, $guards);
        } catch (AuthenticationException $e) {
            throw new AppException('Unauthenticated.', $e, Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    #[Override]
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
