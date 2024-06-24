<?php

namespace tests\Unit\UseCase;

use App\Exceptions\AppException;
use App\UseCase\RegisterUserUseCase;
use Tests\TestCase;

class RegisterUserUseCaseTest extends TestCase
{
    private RegisterUserUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->useCase = app(RegisterUserUseCase::class);
    }

    public function test_register_user_with_encrypt_token_fail(): void
    {
        $_ENV['ENCRYPT_SECRET_KEY'] = '';
        $_ENV['SECRET_IV'] = '';

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Failed to create verify user token');

        $this->useCase->run(['id' => fake()->uuid()]);
    }
}
