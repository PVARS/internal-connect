<?php

namespace tests\Unit\UseCase;

use App\Exceptions\AppException;
use App\Repositories\UserRepositoryInterface;
use App\UseCase\RegisterUserUseCase;
use Exception;
use Mockery;
use Symfony\Component\HttpFoundation\Response as HttpCode;
use Tests\TestCase;

class RegisterUserUseCaseTest extends TestCase
{
    private RegisterUserUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->useCase = app(RegisterUserUseCase::class);

        $_ENV['ENCRYPT_SECRET_KEY'] = 'my_secret_key';
        $_ENV['SECRET_IV'] = 'my_secret_iv';
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function test_register_user_with_encrypt_token_fail(): void
    {
        $_ENV['ENCRYPT_SECRET_KEY'] = '';
        $_ENV['SECRET_IV'] = '';

        $input = ['id' => fake()->uuid()];

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Failed to create verify user token');
        $this->expectExceptionCode(HttpCode::HTTP_INTERNAL_SERVER_ERROR);

        $this->useCase->run($input);
    }

    public function test_register_user_fail(): void
    {
        $input = ['id' => fake()->uuid()];

        $mock = Mockery::mock(UserRepositoryInterface::class);
        $mock
            ->shouldReceive('create')
            ->with($input)
            ->andThrow(Exception::class, 'Register user failed', HttpCode::HTTP_INTERNAL_SERVER_ERROR);

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Register user failed');
        $this->expectExceptionCode(HttpCode::HTTP_INTERNAL_SERVER_ERROR);

        $this->useCase->run($input);
    }
}
