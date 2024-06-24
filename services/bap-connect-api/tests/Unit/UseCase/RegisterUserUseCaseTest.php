<?php

namespace tests\Unit\UseCase;

use App\Exceptions\AppException;
use App\UseCase\RegisterUserUseCase;
use App\Utils\Util;
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
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_register_user_with_encrypt_token_fail(): void
    {
        $input = ['id' => fake()->uuid()];

        $utilMock = Mockery::mock('alias:'.Util::class);
        $utilMock->shouldReceive('opensslEncrypt')
            ->with($input['id'])
            ->andReturn(null);

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Failed to create verify user token');
        $this->expectExceptionCode(HttpCode::HTTP_INTERNAL_SERVER_ERROR);

        $this->useCase->run($input);
    }
}
