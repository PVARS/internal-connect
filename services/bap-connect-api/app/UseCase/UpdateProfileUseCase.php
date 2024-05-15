<?php

namespace App\UseCase;

use App\Exceptions\AppException;
use App\Repositories\UserRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UpdateProfileUseCase
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
     * Update profile user.
     *
     * @param  string  $id  User ID
     * @param  array  $payload  Data update profile user
     *
     * @throws AppException Failed to update
     *
     * @return Model User
     */
    public function run(string $id, array $payload): Model
    {
        try {
            $user = $this->userRepository->findById($id);
            if (!$payload) {
                return $user;
            }

            DB::beginTransaction();
            $user->update($payload);
            DB::commit();

            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw new AppException('Failed to update', $e);
        }
    }
}
