<?php

namespace App\Http\Middleware;

use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class RefreshToken extends BaseMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $newToken = null;
        try {
            $token = $this->auth->parseToken();
            $token->authenticate();
        } catch (TokenExpiredException $e) {
            // Token expired: try refresh
            try {
                $newToken = $token->refresh();
            } catch (JWTException $e) {
                // Refresh failed (refresh expired)
            }
        } catch (JWTException $e) {
            // Invalid token
        }

        $response = $next($request);
        if ($newToken) {
            // Send the refreshed token back to the client.
            $response->withCookie(cookie(
                'token',
                $newToken,
                config('jwt.refresh_ttl'), // minutes
                null, // path
                null, // domain
                $request->getScheme() === 'https', // secure
                true // httpOnly
            ));
        }
        return $response;
    }
}
