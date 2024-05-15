<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HandleRequestMiddleware
{
    /**
     * Inject auth token from cookie.
     *
     * @param  Request  $request  Request
     * @return Request Request
     */
    public function injectAuthTokenFromCookie(Request $request): Request
    {
        if ($jwt = $request->cookie(env('AUTH_COOKIE_NAME'))) {
            $request->headers->set('Authorization', 'Bearer '.$jwt);
        }

        return $request;
    }
}
