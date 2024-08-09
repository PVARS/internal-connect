<?php

namespace tests\Unit\UseCase;

use App\Enums\Gender;
use App\Events\UserRegistered;
use App\Exceptions\AppException;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use App\UseCase\RegisterUserUseCase;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Mockery;
use Symfony\Component\HttpFoundation\Response as HttpCode;
use Tests\TestCase;

class RegisterUserUseCaseTest extends TestCase
{
    private RegisterUserUseCase $useCase;

    private UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->useCase = new RegisterUserUseCase($this->userRepository);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function test_register_user_fail(): void
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];

        $this->userRepository
            ->shouldReceive('create')
            ->withAnyArgs()
            ->andThrow(Exception::class, 'Register user failed', HttpCode::HTTP_INTERNAL_SERVER_ERROR);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('rollBack')->once();

        $this->expectException(AppException::class);
        $this->expectExceptionMessage('Register user failed');
        $this->expectExceptionCode(HttpCode::HTTP_INTERNAL_SERVER_ERROR);

        $this->useCase->run($input);
    }

    public function test_register_user_success(): void
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'email' => fake()->email(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
            'verify_user_token' => 'token',
        ];
        $userExpected = Mockery::mock(User::class);
        $userExpected->shouldReceive('getId')->andReturn($input['id']);
        $userExpected->shouldReceive('getUsername')->andReturn($input['username']);
        $userExpected->shouldReceive('getEmail')->andReturn($input['email']);
        $userExpected->shouldReceive('getFirstName')->andReturn($input['first_name']);
        $userExpected->shouldReceive('getLastName')->andReturn($input['last_name']);
        $userExpected->shouldReceive('getGender')->andReturn($input['gender']);
        $userExpected->shouldReceive('getCreatedBy')->andReturn($input['created_by']);
        $userExpected->shouldReceive('getUpdatedBy')->andReturn($input['updated_by']);
        $userExpected->shouldReceive('getCreatorName')->andReturn($input['creator_name']);
        $userExpected->shouldReceive('getUpdaterName')->andReturn($input['updater_name']);

        $this->userRepository
            ->shouldReceive('create')
            ->with($input)
            ->andReturn($userExpected);

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();

        Event::fake();

        $actual = $this->useCase->run($input);

        $this->assertInstanceOf(User::class, $actual);
        $this->assertEquals($input['id'], $actual->getId());
        $this->assertEquals($input['username'], $actual->getUsername());
        $this->assertEquals($input['email'], $actual->getEmail());
        $this->assertEquals($input['first_name'], $actual->getFirstName());
        $this->assertEquals($input['last_name'], $actual->getLastName());
        $this->assertEquals($input['gender'], $actual->getGender());
        $this->assertEquals($input['created_by'], $actual->getCreatedBy());
        $this->assertEquals($input['updated_by'], $actual->getUpdatedBy());
        $this->assertEquals($input['creator_name'], $actual->getCreatorName());
        $this->assertEquals($input['updater_name'], $actual->getUpdaterName());
        Event::assertDispatchedTimes(UserRegistered::class);
        Event::assertDispatched(UserRegistered::class, fn ($event) => $event->user === $userExpected);
    }
}
