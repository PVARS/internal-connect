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
     * @return string URL avatar
     */
    public function run(array $payload): ?string
    {
        $path = Storage::disk('user-avatars')->put($payload['user_id'], $payload['avatar']);
        throw_if(!$path, new AppException('Failed to upload avatar'));

        try {
            DB::beginTransaction();
            $isUpdated = $this->userRepository->update($payload['user_id'], [
                'avatar' => $path,
                'updated_by' => $payload['user_id'],
                'updater_name' => $payload['username'],
            ]);
            throw_if(!$isUpdated, new AppException('Failed to update avatar'));

            DB::commit();

            $urlAvatar = Storage::disk('user-avatars')->url($path);
            throw_if($urlAvatar, new AppException('Failed to get url avatar'));

            return $urlAvatar;
        } catch (Exception | AppException $e) {
            DB::rollBack();

            Storage::disk('user-avatars')->delete($path);

            if ($e instanceof AppException) {
                throw new AppException($e->getMessage(), $e);
            }

            throw new AppException('Failed to upload avatar', $e);
        }
    }
}
