<?php

namespace App\UseCase;

use App\Exceptions\AppException;
use App\Repositories\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateAvatarUseCase
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
     * Update avatar profile.
     *
     * @param  array  $payload
     *
     * @throws AppException Failed to upload avatar
     *
     * @return void
     */
    public function run(array $payload): void
    {
        $path = Storage::disk('user-avatars')->put($payload['user_id'], $payload['avatar']);
        if (!$path) {
            throw new AppException('Failed to upload avatar');
        }

        try {
            DB::beginTransaction();
            $this->userRepository->update($payload['user_id'], [
                'avatar' => $path,
                'updated_by' => $payload['user_id'],
                'updater_name' => $payload['username'],
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            Storage::disk('user-avatars')->delete($path);

            throw new AppException('Failed to upload avatar', $e);
        }
    }
}
