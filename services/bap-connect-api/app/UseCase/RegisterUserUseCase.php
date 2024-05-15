<?php

namespace App\UseCase;

use App\Events\UserRegistered;
use App\Exceptions\AppException;
use App\Repositories\UserRepositoryInterface;
use App\Utils\Util;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RegisterUserUseCase
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
     * Register new user.
     *
     * @param  array  $payload  Data register new a user
     *
     * @throws AppException Register user failed
     * @throws AppException Failed to create verify user token
     *
     * @return Model New a User
     */
    public function run(array $payload): Model
    {
        $verifyUserToken = Util::opensslEncrypt($payload['id']);
        if (is_null($verifyUserToken)) {
            throw new AppException('Failed to create verify user token');
        }
        $payload['verify_user_token'] = $verifyUserToken;

        try {
            DB::beginTransaction();
            $user = $this->userRepository->create($payload);

            DB::commit();

            event(new UserRegistered($user));

            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw new AppException('Register user failed', $e);
        }
    }
}
