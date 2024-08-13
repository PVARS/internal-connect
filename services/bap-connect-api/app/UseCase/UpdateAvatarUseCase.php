<?php

namespace App\UseCase;

use App\Exceptions\AppException;
use App\Repositories\UserRepositoryInterface;
use App\Utils\Constants;
use Exception;
use Illuminate\Http\UploadedFile;
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
     * @throws AppException Failed to upload avatar to cloud
     * @throws AppException Failed to update avatar
     * @throws AppException Upload failed
     *
     * @return string URL avatar
     */
    public function run(array $payload): string
    {
        $path = $this->uploadAvatar($payload['user_id'], $payload['avatar']);

        try {
            DB::beginTransaction();
            $this->updateUserAvatar($payload, $path);

            DB::commit();

            return Storage::disk(Constants::USER_AVATARS)->url($path);
        } catch (Exception|AppException $e) {
            DB::rollBack();
            $this->handleFailedUpload($path, $e);
        }
    }

    /**
     * Upload avatar to cloud.
     *
     * @param  string  $path  Path
     * @param  UploadedFile  $file  File
     *
     * @throws AppException Failed to upload avatar to cloud
     *
     * @return string Path
     */
    private function uploadAvatar(string $path, UploadedFile $file): string
    {
        $path = Storage::disk(Constants::USER_AVATARS)->put($path, $file);
        if (!$path) {
            throw new AppException('Failed to upload avatar to cloud');
        }

        return $path;
    }

    /**
     * Update path user avatar to db.
     *
     * @param  array  $payload  Payload
     * @param  string  $path  Path avatar
     *
     * @throws AppException Failed to update avatar
     *
     * @return void
     */
    private function updateUserAvatar(array $payload, string $path): void
    {
        $isUpdated = $this->userRepository->update($payload['user_id'], [
            'avatar' => $path,
            'updated_by' => $payload['user_id'],
            'updater_name' => $payload['username'],
        ]);
        if (!$isUpdated) {
            throw new AppException('Failed to update avatar');
        }
    }

    /**
     * Handle failed to upload avatar.
     *
     * @param  string  $path  Path
     * @param  Exception  $e  Exception
     *
     * @throws AppException When upload failed
     *
     * @return void
     */
    private function handleFailedUpload(string $path, Exception $e): void
    {
        Storage::disk(Constants::USER_AVATARS)->delete($path);
        $message = $e instanceof AppException ? $e->getMessage() : 'Upload failed';
        throw new AppException($message, $e);
    }
}
