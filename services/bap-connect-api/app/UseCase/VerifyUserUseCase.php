<?php

namespace App\UseCase;

use App\Exceptions\AppException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepositoryInterface;
use App\Utils\Util;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as HttpCode;

class VerifyUserUseCase
{
    private UserRepositoryInterface $userRepository;

    /**
     * @param  UserRepositoryInterface  $userRepository  UserRepositoryInterface
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Verify user.
     *
     * @param  array  $payload  Data to verify user
     *
     * @throws AppException Token invalid
     * @throws AppException User already verified
     * @throws AppException Token expired
     * @throws AppException Verify user failed
     * @throws UserNotFoundException User not found
     */
    public function run(array $payload): void
    {
        $userId = Util::opensslDecrypt($payload['token']);
        if ($userId === null) {
            throw new AppException('Token invalid', null, HttpCode::HTTP_PRECONDITION_FAILED);
        }

        try {
            $user = $this->userRepository->findById($userId);
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException('User not found', $e);
        }

        if ($user->hasVerifiedEmail()) {
            throw new AppException('User already verified', null, HttpCode::HTTP_FORBIDDEN);
        }
        if ($user['user_verify_token_expiration'] < Carbon::now()) {
            throw new AppException('Token expired', null, HttpCode::HTTP_GONE);
        }

        try {
            DB::beginTransaction();
            $isUpdated = $user->update([
                'password' => Hash::make($payload['password']),
                'email_verified_at' => Carbon::now(),
                'verify_user_token' => null,
                'user_verify_token_expiration' => null,
                'status' => true,
            ]);

            throw_if(!$isUpdated, AppException::class);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new AppException('Verify user failed', $e);
        }
    }
}
