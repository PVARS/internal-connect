<?php

namespace tests\Unit\Repositories;

use App\Enums\Gender;
use App\Enums\UserStatus;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    private UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = app(UserRepositoryInterface::class);
        User::truncate();
    }

    protected function tearDown(): void
    {
        User::truncate();
        parent::tearDown();
    }

    public function test_find_users_normal(): void
    {
        $expected = [
            [
                'id' => fake()->unique()->uuid(),
                'username' => fake()->unique()->userName(),
                'email' => fake()->unique()->email(),
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'gender' => Gender::MALE->value,
                'status' => UserStatus::ACTIVE->value,
                'created_by' => User::SYSTEM_USER_ID,
                'creator_name' => User::SYSTEM_USER_NAME,
                'updated_by' => User::SYSTEM_USER_ID,
                'updater_name' => User::SYSTEM_USER_NAME,
            ],
            [
                'id' => fake()->unique()->uuid(),
                'username' => fake()->unique()->userName(),
                'email' => fake()->unique()->email(),
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'gender' => Gender::MALE->value,
                'status' => UserStatus::ACTIVE->value,
                'created_by' => User::SYSTEM_USER_ID,
                'creator_name' => User::SYSTEM_USER_NAME,
                'updated_by' => User::SYSTEM_USER_ID,
                'updater_name' => User::SYSTEM_USER_NAME,
            ],
        ];
        foreach ($expected as $item) {
            User::factory()->create($item);
        }
        $actual = $this->userRepository->findUsers();

        $this->assertEquals(count($expected), $actual->count());
        foreach ($actual->get()->toArray() as $key => $item) {
            $this->buildAssertUser($expected[$key], $item);
        }
    }

    public function test_find_users_with_empty_data(): void
    {
        $actual = $this->userRepository->findUsers()->get()->toArray();
        $this->assertEmpty($actual);
    }

    public function test_find_users_with_filters(): void
    {
        $expected = [
            [
                'id' => fake()->unique()->uuid(),
                'username' => fake()->unique()->userName(),
                'email' => fake()->unique()->email(),
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'gender' => Gender::MALE->value,
                'status' => UserStatus::ACTIVE->value,
                'created_by' => User::SYSTEM_USER_ID,
                'creator_name' => User::SYSTEM_USER_NAME,
                'updated_by' => User::SYSTEM_USER_ID,
                'updater_name' => User::SYSTEM_USER_NAME,
            ],
            [
                'id' => fake()->unique()->uuid(),
                'username' => fake()->unique()->userName(),
                'email' => fake()->unique()->email(),
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'gender' => Gender::MALE->value,
                'status' => UserStatus::ACTIVE->value,
                'created_by' => User::SYSTEM_USER_ID,
                'creator_name' => User::SYSTEM_USER_NAME,
                'updated_by' => User::SYSTEM_USER_ID,
                'updater_name' => User::SYSTEM_USER_NAME,
            ],
        ];
        foreach ($expected as $item) {
            User::factory()->create($item);
        }
        $actual = $this->userRepository->findUsers([
            'gender' => Gender::MALE->value,
            'status' => UserStatus::ACTIVE->value,
        ]);

        $this->assertEquals(count($expected), $actual->count());
        foreach ($actual->get()->toArray() as $key => $item) {
            $this->buildAssertUser($expected[$key], $item);
        }
    }

    /**
     * Build assert user.
     *
     * @param  array  $expected  Expected data
     * @param  array  $actual  Actual data
     * @return void
     */
    private function buildAssertUser(array $expected, array $actual): void
    {
        $this->assertEquals($expected['id'], $actual['id']);
        $this->assertEquals($expected['username'], $actual['username']);
        $this->assertEquals($expected['email'], $actual['email']);
        $this->assertNull($actual['password']);
        $this->assertNull($actual['avatar']);
        $this->assertEquals($expected['first_name'], $actual['first_name']);
        $this->assertEquals($expected['last_name'], $actual['last_name']);
        $this->assertEquals($expected['gender'], $actual['gender']);
        $this->assertNull($actual['birthday_day']);
        $this->assertNull($actual['birthday_month']);
        $this->assertNull($actual['birthday_year']);
        $this->assertNull($actual['province']);
        $this->assertNull($actual['district']);
        $this->assertNull($actual['ward']);
        $this->assertNull($actual['address']);
        $this->assertNull($actual['phone']);
        $this->assertNull($actual['email_verified_at']);
        $this->assertEquals($expected['status'], $actual['status']);
        $this->assertNull($actual['verify_user_token']);
        $this->assertNull($actual['user_verify_token_expiration']);
        $this->assertNotNull($actual['created_at']);
        $this->assertNotNull($actual['updated_at']);
        $this->assertEquals($expected['created_by'], $actual['created_by']);
        $this->assertEquals($expected['creator_name'], $actual['creator_name']);
        $this->assertEquals($expected['updated_by'], $actual['updated_by']);
        $this->assertEquals($expected['updater_name'], $actual['updater_name']);
        $this->assertNull($actual['deleted_at']);
    }
}
