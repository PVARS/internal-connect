<?php

namespace Tests\Feature\UserControllerTests;

use App\Enums\Gender;
use App\Models\User;
use App\Utils\Constants;
use Symfony\Component\HttpFoundation\Response as HttpCode;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    private string $api;

    private array $headers = [];

    protected function setUp(): void
    {
        parent::setUp();
        $_ENV['ENCRYPT_SECRET_KEY'] = 'my_secret_key';
        $_ENV['SECRET_IV'] = 'my_secret_iv';
        $this->api = route('users.register');
        $this->headers = ['Accept' => 'application/json'];
        User::truncate();
    }

    protected function tearDown(): void
    {
        User::truncate();
        parent::tearDown();
        $_ENV['ENCRYPT_SECRET_KEY'] = 'my_secret_key';
        $_ENV['SECRET_IV'] = 'my_secret_iv';
    }

    public function test_register_user_with_first_name_empty_string()
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'first_name' => '',
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'DATA' => [],
            'MESSAGE' => 'The first name field is required.',
        ]);
    }

    public function test_register_user_with_first_name_is_null()
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'first_name' => null,
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'DATA' => [],
            'MESSAGE' => 'The first name field is required.',
        ]);
    }

    public function test_register_user_with_param_first_name_dont_exists()
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'DATA' => [],
            'MESSAGE' => 'The first name field is required.',
        ]);
    }

    public function test_register_user_with_first_name_is_not_string()
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'first_name' => fake()->numberBetween(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'DATA' => [],
            'MESSAGE' => 'The first name field must be a string.',
        ]);
    }

    public function test_register_user_with_first_name_max_length()
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'first_name' => str_repeat('a', Constants::FIRST_NAME_MAX_LENGTH + 1),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'DATA' => [],
            'MESSAGE' => 'The first name field must not be greater than '.Constants::FIRST_NAME_MAX_LENGTH.' characters.',
        ]);
    }

    public function test_register_user_with_last_name_empty_string()
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'first_name' => fake()->firstName(),
            'last_name' => '',
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'DATA' => [],
            'MESSAGE' => 'The last name field is required.',
        ]);
    }

    public function test_register_user_with_last_name_is_null()
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'first_name' => fake()->firstName(),
            'last_name' => null,
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'DATA' => [],
            'MESSAGE' => 'The last name field is required.',
        ]);
    }

    public function test_register_user_with_param_last_name_dont_exists()
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'first_name' => fake()->firstName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'DATA' => [],
            'MESSAGE' => 'The last name field is required.',
        ]);
    }

    public function test_register_user_with_last_name_is_not_string()
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->numberBetween(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'DATA' => [],
            'MESSAGE' => 'The last name field must be a string.',
        ]);
    }

    public function test_register_user_with_last_name_max_length()
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'first_name' => fake()->firstName(),
            'last_name' => str_repeat('a', Constants::LAST_NAME_MAX_LENGTH + 1),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'DATA' => [],
            'MESSAGE' => 'The last name field must not be greater than '.Constants::LAST_NAME_MAX_LENGTH.' characters.',
        ]);
    }

    public function test_register_user_with_email_empty_string()
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => '',
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'DATA' => [],
            'MESSAGE' => 'The email field is required.',
        ]);
    }

    public function test_register_user_with_email_is_null()
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => null,
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'DATA' => [],
            'MESSAGE' => 'The email field is required.',
        ]);
    }

    public function test_register_user_with_param_email_dont_exists()
    {
        $input = [
            'id' => fake()->uuid(),
            'username' => fake()->username(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'DATA' => [],
            'MESSAGE' => 'The email field is required.',
        ]);
    }

    public function test_register_user_with_email_invalid()
    {
        $invalidEmails = [
            'plainaddress',
            '#@%^%#$@#$@#.com',
            '@example.com',
            'Joe Smith <email@example.com>',
            'email.example.com',
            'email@example@example.com',
            '.email@example.com',
            'email.@example.com',
            'email..email@example.com',
            'あいうえお@example.com',
            'email@example.com (Joe Smith)',
            'email@example',
            'email@-example.com',
            'email@111.222.333.44444',
            'email@example..com',
            'Abc..123@example.com',
            '”(),:;<>[\]@example.com',
            'just”not”right@example.com',
            'this\ is"really"not\allowed@example.com',
        ];

        foreach ($invalidEmails as $email) {
            $input = [
                'id' => fake()->uuid(),
                'username' => fake()->username(),
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => $email,
                'gender' => Gender::MALE->value,
                'created_by' => User::SYSTEM_USER_ID,
                'updated_by' => User::SYSTEM_USER_ID,
                'creator_name' => User::SYSTEM_USER_NAME,
                'updater_name' => User::SYSTEM_USER_NAME,
            ];

            $response = $this->post($this->api, $input, $this->headers);

            $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJson([
                'DATA' => [],
                'MESSAGE' => 'The email field must be a valid email address.',
            ]);
        }
    }

    //    public function test_register_user_with_email_max_length()
    //    {
    //        $input = [
    //            'id' => fake()->uuid(),
    //            'username' => fake()->username(),
    //            'first_name' => fake()->firstName(),
    //            'last_name' => fake()->lastName(),
    //            'email' => str_repeat('a', Constants::EMAIL_MAX_LENGTH) . '@gmail.com',
    //            'gender' => Gender::MALE->value,
    //            'created_by' => User::SYSTEM_USER_ID,
    //            'updated_by' => User::SYSTEM_USER_ID,
    //            'creator_name' => User::SYSTEM_USER_NAME,
    //            'updater_name' => User::SYSTEM_USER_NAME,
    //        ];
    //
    //        $response = $this->post($this->api, $input, $this->headers);
    //
    //        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
    //        $response->assertJson([
    //            'DATA' => [],
    //            'MESSAGE' => 'The email field must not be greater than '.Constants::EMAIL_MAX_LENGTH.' characters.'
    //        ]);
    //    }

    public function test_register_user_with_failed_to_create_token(): void
    {
        $_ENV['ENCRYPT_SECRET_KEY'] = '';
        $_ENV['SECRET_IV'] = '';

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
        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_INTERNAL_SERVER_ERROR);
        $response->assertJson([
            'DATA' => [],
            'MESSAGE' => 'Failed to create verify user token',
        ]);
    }
}
