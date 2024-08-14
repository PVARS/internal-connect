<?php

namespace App\UseCase;

use App\Enums\UserStatus;
use App\Exceptions\AppException;
use App\Exceptions\BusinessException;
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
     * @throws BusinessException Delete user failed
     * @throws AppException Server error
     * @throws UserNotFoundException User not found
     *
     * @return bool bool True if delete was successful, False otherwise.
     */
    public function run(string $id, string $username): bool
    {
        try {
            DB::beginTransaction();
            $isUpdated = $this->userRepository->update($id, [
                'status' => UserStatus::INACTIVE,
                'deleted_at' => Carbon::now(),
                'updater_name' => $username,
                'updated_by' => $id,
            ]);

            if (!$isUpdated) {
                throw new BusinessException('Delete user failed');
            }

            DB::commit();

            JWTAuth::invalidate(JWTAuth::getToken());

            return true;
        } catch (ModelNotFoundException $e) {
            throw new UserNotFoundException('User not found', $e);
        } catch (BusinessException $e) {
            throw $e;
        } catch (Exception $e) {
            DB::rollBack();
            throw new AppException('Server error', $e);
        }
    }
}
