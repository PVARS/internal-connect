<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
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
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
