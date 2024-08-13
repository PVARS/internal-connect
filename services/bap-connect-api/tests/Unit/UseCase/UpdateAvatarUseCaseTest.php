<?php

namespace Tests\Unit\UseCase;

use App\Exceptions\AppException;
use App\Exceptions\CloudStorageException;
use App\Repositories\UserRepositoryInterface;
use App\UseCase\UpdateAvatarUseCase;
use App\Utils\Constants;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Symfony\Component\HttpFoundation\Response as HttpCode;
use Tests\TestCase;

class UpdateAvatarUseCaseTest extends TestCase
{
    private UpdateAvatarUseCase $useCase;

    private UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->useCase = new UpdateAvatarUseCase($this->userRepository);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function test_update_avatar_user_to_cloud_failed(): void
    {
        $input = [
            'user_id' => fake()->uuid(),
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
            'username' => fake()->userName(),
        ];

        Storage::shouldReceive('disk')
            ->once()
            ->with(Constants::USER_AVATARS)
            ->andReturnSelf();

        Storage::shouldReceive('put')
            ->once()
            ->with($input['user_id'], $input['avatar'])
            ->andReturnFalse();

        $this->expectException(CloudStorageException::class);
        $this->expectExceptionMessage('Failed to upload avatar to cloud');
        $this->expectExceptionCode(HttpCode::HTTP_INTERNAL_SERVER_ERROR);

        $this->useCase->run($input);
    }

    public function test_update_avatar_user_to_db_with_return_false(): void
    {
        $input = [
            'user_id' => fake()->uuid(),
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
            'username' => fake()->userName(),
        ];

        Storage::shouldReceive('disk')
            ->times(2)
            ->with(Constants::USER_AVATARS)
            ->andReturnSelf();

        Storage::shouldReceive('put')
            ->once()
            ->withAnyArgs()
            ->andReturnTrue();

        $this->userRepository->shouldReceive('update')
            ->once()
            ->withAnyArgs()
            ->andReturnFalse();

        Storage::shouldReceive('delete')
            ->once()
            ->withAnyArgs()
            ->andReturnTrue();

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollback')->once();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Failed to update avatar');
        $this->expectExceptionCode(HttpCode::HTTP_INTERNAL_SERVER_ERROR);

        $this->useCase->run($input);
    }

    public function test_update_avatar_user_to_db_fail_with_throw_exception(): void
    {
        $input = [
            'user_id' => fake()->uuid(),
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
            'username' => fake()->userName(),
        ];

        Storage::shouldReceive('disk')
            ->times(2)
            ->with(Constants::USER_AVATARS)
            ->andReturnSelf();

        Storage::shouldReceive('put')
            ->once()
            ->withAnyArgs()
            ->andReturnTrue();

        $this->userRepository->shouldReceive('update')
            ->once()
            ->withAnyArgs()
            ->andThrow(Exception::class);

        Storage::shouldReceive('delete')
            ->once()
            ->withAnyArgs()
            ->andReturnTrue();

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollback')->once();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Upload failed');
        $this->expectExceptionCode(HttpCode::HTTP_INTERNAL_SERVER_ERROR);

        $this->useCase->run($input);
    }

    public function test_update_avatar_user_success(): void
    {
        $input = [
            'user_id' => fake()->uuid(),
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
            'username' => fake()->userName(),
        ];
        $expected = fake()->imageUrl();

        Storage::shouldReceive('disk')
            ->times(2)
            ->with(Constants::USER_AVATARS)
            ->andReturnSelf();

        Storage::shouldReceive('put')
            ->once()
            ->withAnyArgs()
            ->andReturnTrue();

        Storage::shouldReceive('url')
            ->once()
            ->withAnyArgs()
            ->andReturn($expected);

        $this->userRepository->shouldReceive('update')
            ->once()
            ->withAnyArgs()
            ->andReturn(fake()->imageUrl());

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        $actual = $this->useCase->run($input);

        $this->assertEquals($expected, $actual);
    }
}
