<?php

namespace App\UseCase;

use App\Exceptions\BusinessException;
use Symfony\Component\HttpFoundation\Response as HttpCode;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RefreshTokenUseCase
{
    /**
     * Refresh token.
     *
     * @throws BusinessException Token not provided | Failed to refresh token
     *
     * @return string Access token
     */
    public function run(): string
    {
        try {
            $currentToken = JWTAuth::getToken();
            if (!$currentToken) {
                throw new BusinessException('Token not provided', null, HttpCode::HTTP_UNAUTHORIZED);
            }

            return JWTAuth::refresh($currentToken);
        } catch (JWTException $e) {
            throw new BusinessException('Failed to refresh token', $e, HttpCode::HTTP_UNAUTHORIZED);
        }
    }
}
