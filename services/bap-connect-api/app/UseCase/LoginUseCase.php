<?php

namespace App\UseCase;

use App\Exceptions\AppException;
use App\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response as HttpCode;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginUseCase
{
    private UserRepositoryInterface $userRepository;

    /**
     * Constructor.
     *
     * @param  UserRepositoryInterface  $userRepository  UserRepositoryInterface
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Login.
     *
     * @param  array  $payload  Email and password
     *
     * @throws AppException Username and password is incorrect | Failed to generate token
     *
     * @return array User logged
     */
    public function run(array $payload): array
    {
        try {
            $accessToken = JWTAuth::attempt($payload);
        } catch (JWTException $e) {
            throw new AppException('Failed to generate token', $e, $e->getCode());
        }

        if (!$accessToken) {
            throw new AppException('Username and password is incorrect', null,
                HttpCode::HTTP_UNAUTHORIZED);
        }

        $user = $this->userRepository->findById(auth()->id());
        if (!$user['status']) {
            throw new AppException('Username and password is incorrect', null,
                HttpCode::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($user, $accessToken);
    }

    /**
     * Get the token array structure.
     *
     * @param  Model  $user  User logged
     * @param  string  $accessToken  Access token
     * @return array User info and token
     */
    private function respondWithToken(Model $user, string $accessToken): array
    {
        return ['user' => $user, 'access_token' => $accessToken];
    }
}
