<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid(),
            'avatar' => $this->faker->imageUrl(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'birthday_day' => $this->faker->numberBetween(1, 30),
            'birthday_month' => $this->faker->numberBetween(1, 12),
            'birthday_year' => $this->faker->numberBetween(1990, 9999),
            'gender' => $this->faker->numberBetween(0, 2),
            'username' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('12345678'),
            'email_verified_at' => $this->faker->dateTime(),
            'status' => true,
            'created_by' => '00000001-0000-7000-8000-000000000001',
            'updated_by' => '00000001-0000-7000-8000-000000000001',
            'creator_name' => 'Administrator',
            'updater_name' => 'Administrator',
        ];
    }
}
