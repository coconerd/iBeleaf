<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role_type' => $this->faker->numberBetween(0, 1),
            'email' => $this->faker->unique()->safeEmail(),
            'full_name' => $this->faker->name(),
            'user_name' => $this->faker->unique()->userName(),
            'password' => static::$password ??= Hash::make('password'),
			'phone_number' => substr($this->faker->phoneNumber(), 0, 10),
            'province_city' => $this->faker->city(),
            'district' => $this->faker->streetName(),
            'commune_ward' => $this->faker->streetAddress(),
            'address' => $this->faker->address(),
            'gender' => $this->faker->randomElement(['Nam', 'Nữ', 'Khác']),
            'date_of_birth' => $this->faker->date(),
            'avatar' => $this->faker->imageUrl(200, 200, 'people'),
            'card_id' => null
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
