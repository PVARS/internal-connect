<?php

namespace App\Http\Middleware;

use App\Exceptions\AppException;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class EnsureEmailIsVerifiedAndEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @throws AppException User is not verified
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && (($request->user() instanceof MustVerifyEmail && !$request->user()->hasVerifiedEmail()) || !$request->user()->status)) {
            JWTAuth::invalidate(JWTAuth::getToken());
            throw new AppException('Please login again.', null, Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
