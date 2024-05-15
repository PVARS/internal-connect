<?php

namespace App\UseCase;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response as HttpCode;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LogoutUseCase
{
    /**
     * Logout.
     *
     * @throws AppException Failed to logout
     *
     * @return void
     */
    public function run(): void
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            throw new AppException('Failed to logout, please try again.', $e, HttpCode::HTTP_UNAUTHORIZED);
        }
    }
}
