<?php

namespace tests\Unit\Repositories;

use App\Enums\Gender;
use App\Enums\UserStatus;
use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
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
        $actual = $this->userRepository->findUsers()->get()->toArray();

        $this->assertSameSize($expected, $actual);
        foreach ($actual as $key => $item) {
            $this->buildAssertUser($expected[$key], $item);
        }
    }

    public function test_find_users_with_empty_data(): void
    {
        $actual = $this->userRepository->findUsers()->get()->toArray();
        $this->assertEmpty($actual);
    }

    public function test_find_users_with_filter_gender(): void
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
                'gender' => Gender::FEMALE->value,
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
                'gender' => Gender::OTHER->value,
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
            'gender' => Gender::MALE->value
        ])->get()->toArray();

        $this->assertCount(1, $actual);
        $this->buildAssertUser($expected[0], $actual[0]);
    }

    public function test_find_users_with_filter_first_name(): void
    {
        $expected = [
            [
                'id' => fake()->unique()->uuid(),
                'username' => fake()->unique()->userName(),
                'email' => fake()->unique()->email(),
                'first_name' => 'David',
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
                'first_name' => 'Kevin',
                'last_name' => fake()->lastName(),
                'gender' => Gender::FEMALE->value,
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
            'first_name' => 'Dav'
        ])->get()->toArray();

        $this->assertCount(1, $actual);
        $this->buildAssertUser($expected[0], $actual[0]);
    }

    public function test_find_users_with_filter_last_name(): void
    {
        $expected = [
            [
                'id' => fake()->unique()->uuid(),
                'username' => fake()->unique()->userName(),
                'email' => fake()->unique()->email(),
                'first_name' => fake()->firstName(),
                'last_name' => 'Ronaldo',
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
                'last_name' => 'Messi',
                'gender' => Gender::FEMALE->value,
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
            'last_name' => 'Mess'
        ])->get()->toArray();

        $this->assertCount(1, $actual);
        $this->buildAssertUser($expected[1], $actual[0]);
    }

    public function test_find_users_with_filter_birthday_from(): void
    {
        $birthday1 = Carbon::now()->addDay();
        $birthday2 = Carbon::parse('Now -1 day');
        $expected = [
            [
                'id' => fake()->unique()->uuid(),
                'username' => fake()->unique()->userName(),
                'email' => fake()->unique()->email(),
                'first_name' => fake()->firstName(),
                'last_name' => 'Ronaldo',
                'gender' => Gender::MALE->value,
                'status' => UserStatus::ACTIVE->value,
                'created_by' => User::SYSTEM_USER_ID,
                'creator_name' => User::SYSTEM_USER_NAME,
                'updated_by' => User::SYSTEM_USER_ID,
                'updater_name' => User::SYSTEM_USER_NAME,
                'birthday_day' => $birthday1->day,
                'birthday_month' => $birthday1->month,
                'birthday_year' => $birthday1->year,
            ],
            [
                'id' => fake()->unique()->uuid(),
                'username' => fake()->unique()->userName(),
                'email' => fake()->unique()->email(),
                'first_name' => fake()->firstName(),
                'last_name' => 'Messi',
                'gender' => Gender::FEMALE->value,
                'status' => UserStatus::ACTIVE->value,
                'created_by' => User::SYSTEM_USER_ID,
                'creator_name' => User::SYSTEM_USER_NAME,
                'updated_by' => User::SYSTEM_USER_ID,
                'updater_name' => User::SYSTEM_USER_NAME,
                'birthday_day' => $birthday2->day,
                'birthday_month' => $birthday2->month,
                'birthday_year' => $birthday2->year,
            ],
        ];
        foreach ($expected as $item) {
            User::factory()->create($item);
        }
        $actual = $this->userRepository->findUsers([
            'birthday_from' => Carbon::now()
        ])->get()->toArray();

        $this->assertCount(1, $actual);
        $this->assertEquals($expected[0]['id'], $actual[0]['id']);
        $this->assertEquals($expected[0]['username'], $actual[0]['username']);
        $this->assertEquals($expected[0]['email'], $actual[0]['email']);
        $this->assertNull($actual[0]['password']);
        $this->assertNull($actual[0]['avatar']);
        $this->assertEquals($expected[0]['first_name'], $actual[0]['first_name']);
        $this->assertEquals($expected[0]['last_name'], $actual[0]['last_name']);
        $this->assertEquals($expected[0]['gender'], $actual[0]['gender']);
        $this->assertEquals($expected[0]['birthday_day'], $actual[0]['birthday_day']);
        $this->assertEquals($expected[0]['birthday_month'], $actual[0]['birthday_month']);
        $this->assertEquals($expected[0]['birthday_year'], $actual[0]['birthday_year']);
        $this->assertNull($actual[0]['province']);
        $this->assertNull($actual[0]['district']);
        $this->assertNull($actual[0]['ward']);
        $this->assertNull($actual[0]['address']);
        $this->assertNull($actual[0]['phone']);
        $this->assertNull($actual[0]['email_verified_at']);
        $this->assertEquals($expected[0]['status'], $actual[0]['status']);
        $this->assertNull($actual[0]['verify_user_token']);
        $this->assertNull($actual[0]['user_verify_token_expiration']);
        $this->assertNotNull($actual[0]['created_at']);
        $this->assertNotNull($actual[0]['updated_at']);
        $this->assertEquals($expected[0]['created_by'], $actual[0]['created_by']);
        $this->assertEquals($expected[0]['creator_name'], $actual[0]['creator_name']);
        $this->assertEquals($expected[0]['updated_by'], $actual[0]['updated_by']);
        $this->assertEquals($expected[0]['updater_name'], $actual[0]['updater_name']);
        $this->assertNull($actual[0]['deleted_at']);
    }

    public function test_find_users_with_filter_birthday_to(): void
    {
        $birthday1 = Carbon::now()->addDay();
        $birthday2 = Carbon::parse('Now -1 day');
        $expected = [
            [
                'id' => fake()->unique()->uuid(),
                'username' => fake()->unique()->userName(),
                'email' => fake()->unique()->email(),
                'first_name' => fake()->firstName(),
                'last_name' => 'Ronaldo',
                'gender' => Gender::MALE->value,
                'status' => UserStatus::ACTIVE->value,
                'created_by' => User::SYSTEM_USER_ID,
                'creator_name' => User::SYSTEM_USER_NAME,
                'updated_by' => User::SYSTEM_USER_ID,
                'updater_name' => User::SYSTEM_USER_NAME,
                'birthday_day' => $birthday1->day,
                'birthday_month' => $birthday1->month,
                'birthday_year' => $birthday1->year,
            ],
            [
                'id' => fake()->unique()->uuid(),
                'username' => fake()->unique()->userName(),
                'email' => fake()->unique()->email(),
                'first_name' => fake()->firstName(),
                'last_name' => 'Messi',
                'gender' => Gender::FEMALE->value,
                'status' => UserStatus::ACTIVE->value,
                'created_by' => User::SYSTEM_USER_ID,
                'creator_name' => User::SYSTEM_USER_NAME,
                'updated_by' => User::SYSTEM_USER_ID,
                'updater_name' => User::SYSTEM_USER_NAME,
                'birthday_day' => $birthday2->day,
                'birthday_month' => $birthday2->month,
                'birthday_year' => $birthday2->year,
            ],
        ];
        foreach ($expected as $item) {
            User::factory()->create($item);
        }
        $actual = $this->userRepository->findUsers([
            'birthday_to' => Carbon::now()
        ])->get()->toArray();

        $this->assertCount(1, $actual);
        $this->assertEquals($expected[1]['id'], $actual[0]['id']);
        $this->assertEquals($expected[1]['username'], $actual[0]['username']);
        $this->assertEquals($expected[1]['email'], $actual[0]['email']);
        $this->assertNull($actual[0]['password']);
        $this->assertNull($actual[0]['avatar']);
        $this->assertEquals($expected[1]['first_name'], $actual[0]['first_name']);
        $this->assertEquals($expected[1]['last_name'], $actual[0]['last_name']);
        $this->assertEquals($expected[1]['gender'], $actual[0]['gender']);
        $this->assertEquals($expected[1]['birthday_day'], $actual[0]['birthday_day']);
        $this->assertEquals($expected[1]['birthday_month'], $actual[0]['birthday_month']);
        $this->assertEquals($expected[1]['birthday_year'], $actual[0]['birthday_year']);
        $this->assertNull($actual[0]['province']);
        $this->assertNull($actual[0]['district']);
        $this->assertNull($actual[0]['ward']);
        $this->assertNull($actual[0]['address']);
        $this->assertNull($actual[0]['phone']);
        $this->assertNull($actual[0]['email_verified_at']);
        $this->assertEquals($expected[1]['status'], $actual[0]['status']);
        $this->assertNull($actual[0]['verify_user_token']);
        $this->assertNull($actual[0]['user_verify_token_expiration']);
        $this->assertNotNull($actual[0]['created_at']);
        $this->assertNotNull($actual[0]['updated_at']);
        $this->assertEquals($expected[1]['created_by'], $actual[0]['created_by']);
        $this->assertEquals($expected[1]['creator_name'], $actual[0]['creator_name']);
        $this->assertEquals($expected[1]['updated_by'], $actual[0]['updated_by']);
        $this->assertEquals($expected[1]['updater_name'], $actual[0]['updater_name']);
        $this->assertNull($actual[0]['deleted_at']);
    }

    public function test_find_users_with_filter_username(): void
    {
        $expected = [
            [
                'id' => fake()->unique()->uuid(),
                'username' => 'test1',
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
                'username' => 'test2',
                'email' => fake()->unique()->email(),
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'gender' => Gender::FEMALE->value,
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
            'username' => 'test1'
        ])->get()->toArray();

        $this->assertCount(1, $actual);
        $this->buildAssertUser($expected[0], $actual[0]);
    }

    public function test_find_users_with_filter_email(): void
    {
        $expected = [
            [
                'id' => fake()->unique()->uuid(),
                'username' => fake()->unique()->userName(),
                'email' => 'test@gmail.com',
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
                'email' => 'test+2@gmail.com',
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'gender' => Gender::FEMALE->value,
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
            'email' => 'test+2@gmail.com'
        ])->get()->toArray();

        $this->assertCount(1, $actual);
        $this->buildAssertUser($expected[1], $actual[0]);
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
