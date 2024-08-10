<?php

namespace Tests\Feature\UserControllerTests;

use App\Enums\Gender;
use App\Models\User;
use App\Utils\Constants;
use Carbon\Carbon;
use Illuminate\Support\Str;
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
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The first name field is required.',
        ]);
    }

    public function test_register_user_with_first_name_is_null()
    {
        $input = [
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
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The first name field is required.',
        ]);
    }

    public function test_register_user_with_param_first_name_dont_exists()
    {
        $input = [
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
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The first name field is required.',
        ]);
    }

    public function test_register_user_with_first_name_is_not_string()
    {
        $input = [
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
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The first name field must be a string.',
        ]);
    }

    public function test_register_user_with_first_name_max_length()
    {
        $input = [
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
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The first name field must not be greater than '.Constants::FIRST_NAME_MAX_LENGTH.' characters.',
        ]);
    }

    public function test_register_user_with_last_name_empty_string()
    {
        $input = [
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
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The last name field is required.',
        ]);
    }

    public function test_register_user_with_last_name_is_null()
    {
        $input = [
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
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The last name field is required.',
        ]);
    }

    public function test_register_user_with_param_last_name_dont_exists()
    {
        $input = [
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
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The last name field is required.',
        ]);
    }

    public function test_register_user_with_last_name_is_not_string()
    {
        $input = [
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
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The last name field must be a string.',
        ]);
    }

    public function test_register_user_with_last_name_max_length()
    {
        $input = [
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
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The last name field must not be greater than '.Constants::LAST_NAME_MAX_LENGTH.' characters.',
        ]);
    }

    public function test_register_user_with_email_empty_string()
    {
        $input = [
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
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The email field is required.',
        ]);
    }

    public function test_register_user_with_email_is_null()
    {
        $input = [
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
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The email field is required.',
        ]);
    }

    public function test_register_user_with_param_email_dont_exists()
    {
        $input = [
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
        $response->assertExactJson([
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
            $response->assertExactJson([
                'DATA' => [],
                'MESSAGE' => 'The email field must be a valid email address.',
            ]);
        }
    }

    public function test_register_user_with_email_already_exists()
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        User::factory()->create(array_merge($input, ['username' => fake()->username()]));

        $response = $this->post($this->api, array_merge($input, ['username' => fake()->username()]), $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The email has already been taken.',
        ]);
    }

    public function test_register_user_with_gender_empty(): void
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->username(),
            'email' => fake()->email(),
            'gender' => '',
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];

        $response = $this->post($this->api, array_merge($input), $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The gender field is required.',
        ]);
    }

    public function test_register_user_with_gender_null(): void
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->username(),
            'email' => fake()->email(),
            'gender' => null,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];

        $response = $this->post($this->api, array_merge($input), $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The gender field is required.',
        ]);
    }

    public function test_register_user_with_param_gender_dont_exists(): void
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->username(),
            'email' => fake()->email(),
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];

        $response = $this->post($this->api, array_merge($input), $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The gender field is required.',
        ]);
    }

    public function test_register_user_with_gender_invalid(): void
    {
        $invalidGenders = [random_int(3, PHP_INT_MAX), 'test'];

        foreach ($invalidGenders as $gender) {
            $input = [
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'username' => fake()->username(),
                'gender' => $gender,
                'email' => fake()->email(),
                'created_by' => User::SYSTEM_USER_ID,
                'updated_by' => User::SYSTEM_USER_ID,
                'creator_name' => User::SYSTEM_USER_NAME,
                'updater_name' => User::SYSTEM_USER_NAME,
            ];

            $response = $this->post($this->api, array_merge($input), $this->headers);

            $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertExactJson([
                'DATA' => [],
                'MESSAGE' => 'The selected gender is invalid.',
            ]);
        }
    }

    public function test_register_user_with_username_empty_string(): void
    {
        $input = [
            'username' => '',
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

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The username field is required.',
        ]);
    }

    public function test_register_user_with_username_is_null(): void
    {
        $input = [
            'username' => null,
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

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The username field is required.',
        ]);
    }

    public function test_register_user_with_param_username_dont_exists(): void
    {
        $input = [
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

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The username field is required.',
        ]);
    }

    public function test_register_user_with_username_max_length(): void
    {
        $input = [
            'username' => str_repeat('a', Constants::USERNAME_MAX_LENGTH + 1),
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

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The username field must not be greater than '.Constants::USERNAME_MAX_LENGTH.' characters.',
        ]);
    }

    public function test_register_user_with_username_already_exists(): void
    {
        $input = [
            'username' => fake()->userName(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];
        User::factory()->create(array_merge($input, ['email' => fake()->email()]));

        $response = $this->post($this->api, array_merge($input, ['email' => fake()->email()]), $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The username has already been taken.',
        ]);
    }

    public function test_register_user_with_birthday_is_null(): void
    {
        $input = [
            'username' => fake()->userName(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
            'birthday' => null,
        ];

        $response = $this->post($this->api, $input, $this->headers);
        $actual = $response->json();
        $actualData = $actual['DATA'];

        $response->assertStatus(HttpCode::HTTP_CREATED);
        $this->buildAssertsUserInclude(fn () => $this->assertNull($actualData['birthday']), $input, $actualData, $actual['MESSAGE']);
    }

    public function test_register_user_with_birthday_is_not_date(): void
    {
        $invalidsBirthday = ['test', 12123123, '@!@#$%^^7^$', fake()->email()];

        foreach ($invalidsBirthday as $item) {
            $input = [
                'username' => fake()->userName(),
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->email(),
                'gender' => Gender::MALE->value,
                'created_by' => User::SYSTEM_USER_ID,
                'updated_by' => User::SYSTEM_USER_ID,
                'creator_name' => User::SYSTEM_USER_NAME,
                'updater_name' => User::SYSTEM_USER_NAME,
                'birthday' => $item,
            ];

            $response = $this->post($this->api, $input, $this->headers);

            $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertExactJson([
                'DATA' => [],
                'MESSAGE' => 'The birthday field must be a valid date.',
            ]);
        }
    }

    public function test_register_user_with_birthday_invalid_format(): void
    {
        $invalidsBirthday = [
            '2024/08/10',
            '08-10-2024',
            '2024-08',
            '2024-10',
            '2024-08-10-15',
            '24-08-10',
            '20248-08-10',
            '2024-08-1o',
            '2024-8-10',
            '2024-08-1',
        ];

        foreach ($invalidsBirthday as $item) {
            $input = [
                'username' => fake()->userName(),
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->email(),
                'gender' => Gender::MALE->value,
                'created_by' => User::SYSTEM_USER_ID,
                'updated_by' => User::SYSTEM_USER_ID,
                'creator_name' => User::SYSTEM_USER_NAME,
                'updater_name' => User::SYSTEM_USER_NAME,
                'birthday' => $item,
            ];

            $response = $this->post($this->api, $input, $this->headers);

            $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertExactJson([
                'DATA' => [],
                'MESSAGE' => 'The birthday field must match the format Y-m-d.',
            ]);
        }
    }

    public function test_register_user_with_birthday_in_future(): void
    {
        $input = [
            'username' => fake()->userName(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
            'birthday' => Carbon::now()->addDay()->format(Constants::DATE_FORMAT_ISO),
        ];

        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The birthday field must be a date before or equal to today.',
        ]);
    }

    public function test_register_user_with_phone_is_null(): void
    {
        $input = [
            'username' => fake()->userName(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
            'phone' => null,
        ];

        $response = $this->post($this->api, $input, $this->headers);
        $actual = $response->json();
        $actualData = $actual['DATA'];

        $response->assertStatus(HttpCode::HTTP_CREATED);
        $this->buildAssertsUserInclude(fn () => $this->assertNull($actualData['phone']), $input, $actualData, $actual['MESSAGE']);
    }

    public function test_register_user_with_phone_already_exists(): void
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
            'phone' => fake()->phoneNumber(),
        ];

        User::factory()->create($input);

        $response = $this->post($this->api, array_merge($input,
            ['username' => fake()->userName(), 'email' => fake()->email()]), $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The phone has already been taken.',
        ]);
    }

    public function test_register_user_with_phone_max_length(): void
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
            'phone' => str_repeat(0, Constants::PHONE_MAX_LENGTH + 1),
        ];

        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The phone field must not be greater than '.Constants::PHONE_MAX_LENGTH.' characters.',
        ]);
    }

    public function test_register_user_with_province_is_null(): void
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
            'province' => null,
        ];

        $response = $this->post($this->api, $input, $this->headers);
        $actual = $response->json();
        $actualData = $actual['DATA'];

        $response->assertStatus(HttpCode::HTTP_CREATED);
        $this->buildAssertsUserInclude(fn () => $this->assertNull($actualData['province']), $input, $actualData, $actual['MESSAGE']);
    }

    public function test_register_user_with_province_max_length(): void
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
            'province' => str_repeat('a', Constants::PROVINCE_MAX_LENGTH + 1),
        ];

        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The province field must not be greater than '.Constants::PROVINCE_MAX_LENGTH.' characters.',
        ]);
    }

    public function test_register_user_with_district_is_null(): void
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
            'district' => null,
        ];

        $response = $this->post($this->api, $input, $this->headers);
        $actual = $response->json();
        $actualData = $actual['DATA'];

        $response->assertStatus(HttpCode::HTTP_CREATED);
        $this->buildAssertsUserInclude(fn () => $this->assertNull($actualData['district']), $input, $actualData, $actual['MESSAGE']);
    }

    public function test_register_user_with_district_max_length(): void
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
            'district' => str_repeat('a', Constants::DISTRICT_MAX_LENGTH + 1),
        ];

        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The district field must not be greater than '.Constants::DISTRICT_MAX_LENGTH.' characters.',
        ]);
    }

    public function test_register_user_with_ward_is_null(): void
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
            'ward' => null,
        ];

        $response = $this->post($this->api, $input, $this->headers);
        $actual = $response->json();
        $actualData = $actual['DATA'];

        $response->assertStatus(HttpCode::HTTP_CREATED);
        $this->buildAssertsUserInclude(fn () => $this->assertNull($actualData['ward']), $input, $actualData, $actual['MESSAGE']);
    }

    public function test_register_user_with_ward_max_length(): void
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
            'ward' => str_repeat('a', Constants::WARD_MAX_LENGTH + 1),
        ];

        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The ward field must not be greater than '.Constants::WARD_MAX_LENGTH.' characters.',
        ]);
    }

    public function test_register_user_with_address_is_null(): void
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
            'address' => null,
        ];

        $response = $this->post($this->api, $input, $this->headers);
        $actual = $response->json();
        $actualData = $actual['DATA'];

        $response->assertStatus(HttpCode::HTTP_CREATED);

        $this->buildAssertsUserInclude(fn () => $this->assertNull($actualData['address']), $input, $actualData, $actual['MESSAGE']);
    }

    public function test_register_user_with_address_max_length(): void
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
            'email' => fake()->email(),
            'gender' => Gender::MALE->value,
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
            'address' => str_repeat('a', Constants::ADDRESS_MAX_LENGTH + 1),
        ];

        $response = $this->post($this->api, $input, $this->headers);

        $response->assertStatus(HttpCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'The address field must not be greater than '.Constants::ADDRESS_MAX_LENGTH.' characters.',
        ]);
    }

    public function test_register_user_with_failed_to_create_token(): void
    {
        $_ENV['ENCRYPT_SECRET_KEY'] = '';
        $_ENV['SECRET_IV'] = '';

        $input = [
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
        $response->assertExactJson([
            'DATA' => [],
            'MESSAGE' => 'Failed to create verify user token',
        ]);
    }

    public function test_register_user_with_normal_case(): void
    {
        $input = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'gender' => random_int(0, 2),
            'username' => fake()->userName(),
            'verify_user_token' => Str::random(),
            'user_verify_token_expiration' => Carbon::now()->addDay(),
            'birthday' => Carbon::now()->format(Constants::DATE_FORMAT_ISO),
            'phone' => fake()->phoneNumber(),
            'province' => fake()->city(),
            'district' => fake()->address(),
            'ward' => fake()->address(),
            'address' => fake()->address(),
            'created_by' => User::SYSTEM_USER_ID,
            'updated_by' => User::SYSTEM_USER_ID,
            'creator_name' => User::SYSTEM_USER_NAME,
            'updater_name' => User::SYSTEM_USER_NAME,
        ];

        $response = $this->post($this->api, $input, $this->headers);
        $actual = $response->json();
        $actualData = $actual['DATA'];

        $response->assertStatus(HttpCode::HTTP_CREATED);
        $this->buildAssertsUser($input, $actualData, $actual['MESSAGE']);
    }

    /**
     * Build asserts user.
     *
     * @param  array  $expected  Expected
     * @param  array  $actual  Actual
     * @param  string|null  $message  Message
     * @return void
     */
    private function buildAssertsUser(array $expected, array $actual, ?string $message): void
    {
        $this->assertEquals($expected['username'], $actual['username']);
        $this->assertEquals($expected['first_name'], $actual['first_name']);
        $this->assertEquals($expected['last_name'], $actual['last_name']);
        $this->assertEquals($expected['email'], $actual['email']);
        $this->assertEquals($expected['created_by'], $actual['created_by']);
        $this->assertEquals($expected['updated_by'], $actual['updated_by']);
        $this->assertEquals($expected['creator_name'], $actual['creator_name']);
        $this->assertEquals($expected['updater_name'], $actual['updater_name']);
        $this->assertEquals($expected['gender'], $actual['gender']);
        $this->assertNull($message);
    }

    /**
     * Asserts user with more test.
     *
     * @param  callable  $callback
     * @param  array  $expected
     * @param  array  $actual
     * @param  string|null  $message
     * @return void
     */
    private function buildAssertsUserInclude(callable $callback, array $expected, array $actual, ?string $message): void
    {
        $this->buildAssertsUser($expected, $actual, $message);
        $callback();
    }
}
