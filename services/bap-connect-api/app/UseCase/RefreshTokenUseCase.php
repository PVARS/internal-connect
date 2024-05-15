<?php

namespace App\UseCase;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response as HttpCode;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshTokenUseCase
{
    /**
     * Refresh token.
     *
     * @throws AppException Failed to refresh token
     *
     * @return string Access token
     */
    public function run(): string
    {
        try {
            $currentToken = JWTAuth::getToken();
            if (!$currentToken) {
                throw new AppException('Token not provided', null, HttpCode::HTTP_UNAUTHORIZED);
            }

            return JWTAuth::refresh($currentToken);
        } catch (JWTException $e) {
            throw new AppException('Failed to refresh token', $e, HttpCode::HTTP_UNAUTHORIZED);
        }
    }
}
