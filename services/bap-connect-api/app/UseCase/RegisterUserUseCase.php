<?php

namespace App\UseCase;

use App\Events\UserRegistered;
use App\Exceptions\AppException;
use App\Repositories\UserRepositoryInterface;
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
     *
     * @return Model New a User
     */
    public function run(array $payload): Model
    {
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
