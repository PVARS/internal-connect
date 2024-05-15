<?php

namespace App\UseCase;

use App\Enums\UserStatus;
use App\Exceptions\AppException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class DeleteUserUseCase
{
    private UserRepository $userRepository;

    /**
     * Constructor.
     *
     * @param  UserRepository  $userRepository  UserRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Delete user.
     *
     * @param  string  $id  User ID
     * @param  string  $username  User name
     *
     * @throws AppException Failed to delete
     * @throws UserNotFoundException User not found
     *
     * @return bool bool True if delete was successful, False otherwise.
     */
    public function run(string $id, string $username): bool
    {
        try {
            $user = $this->userRepository->findById($id);

            DB::beginTransaction();
            $isUpdated = $user->update([
                'status' => UserStatus::INACTIVE,
                'deleted_at' => Carbon::now(),
                'updater_name' => $username,
                'updated_by' => $id,
            ]);

            throw_if(!$isUpdated, AppException::class);

            DB::commit();

            JWTAuth::invalidate(JWTAuth::getToken());

            return true;
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException('User not found', $e);
        } catch (Exception $e) {
            DB::rollBack();
            throw new AppException('Failed to delete', $e);
        }
    }
}
