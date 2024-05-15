<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AuthenticatedResource;
use App\Http\Resources\UserResource;
use App\UseCase\LoginUseCase;
use App\UseCase\LogoutUseCase;
use App\UseCase\RefreshTokenUseCase;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private LoginUseCase $loginUseCase;

    private RefreshTokenUseCase $refreshTokenUseCase;

    private LogoutUseCase $logoutUseCase;

    /**
     * Constructor.
     *
     * @param  LoginUseCase  $loginUseCase  Login use case
     * @param  RefreshTokenUseCase  $refreshTokenUseCase  Refresh token use case
     * @param  LogoutUseCase  $logoutUseCase  Logout use case
     */
    public function __construct(LoginUseCase $loginUseCase, RefreshTokenUseCase $refreshTokenUseCase, LogoutUseCase $logoutUseCase)
    {
        $this->loginUseCase = $loginUseCase;
        $this->refreshTokenUseCase = $refreshTokenUseCase;
        $this->logoutUseCase = $logoutUseCase;
    }

    /**
     * Login.
     *
     * @param  LoginRequest  $request  Email and password
     *
     * @throws AppException Username and password is incorrect | Failed to generate token
     *
     * @return JsonResponse User info
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $payload = [
            'email' => $request['email'],
            'password' => $request['password'],
        ];
        $data = $this->loginUseCase->run($payload);

        return $this->ok(new UserResource($data['user']))->withCookie(cookie(
            env('AUTH_COOKIE_NAME'),
            $data['access_token'],
            config('jwt.refresh_ttl'),
            null,
            null,
            !env('APP_DEBUG'),
            true,
            false,
            'Strict'));
    }

    /**
     * Logout.
     *
     * @throws AppException Failed to logout
     *
     * @return JsonResponse Status of logout
     */
    public function logout(): JsonResponse
    {
        $this->logoutUseCase->run();

        return $this->ok([], 'Successfully logged out')->withCookie(cookie(env('AUTH_COOKIE_NAME')));
    }

    /**
     * Refresh token.
     *
     * @throws AppException Failed to refresh token
     *
     * @return JsonResponse Access token
     */
    public function refresh(): JsonResponse
    {
        return $this->ok()->withCookie(cookie(
            env('AUTH_COOKIE_NAME'),
            $this->refreshTokenUseCase->run(),
            config('jwt.refresh_ttl'),
            null,
            null,
            !env('APP_DEBUG'),
            true,
            false,
            'Strict'));
    }

    /**
     * Confirms that the user has been successfully authenticated.
     * This method returns a JSON response indicating successful authentication.
     *
     * @return JsonResponse
     */
    public function authenticated(): JsonResponse
    {
        return $this->ok(new AuthenticatedResource(true));
    }
}
