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
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
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

    /**
     * Create a student user with all student-specific fields populated.
     */
    public function student(): static
    {
        static $counter = 1000;

        return $this->state(function (array $attributes) use (&$counter) {
            $firstName = fake()->firstName();
            $lastName = fake()->lastName();

            return [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'name' => $firstName.' '.$lastName,
                'student_id' => '2024-'.str_pad((string) $counter++, 4, '0', STR_PAD_LEFT),
                'program' => fake()->randomElement(['BSIT', 'BSCS', 'BSCE', 'BSBA', 'BSCRIM', 'BSHM', 'BSED']),
                'college' => fake()->randomElement([
                    'COLLEGE OF BUSINESS ENTREPRENEURSHIP AND ACCOUNTANCY',
                    'COLLEGE OF CRIMINAL JUSTICE EDUCATION',
                    'COLLEGE OF FISHERIES AND AQUATIC SCIENCES',
                    'COLLEGE OF HOSPITALITY MANAGEMENT',
                    'COLLEGE OF INDUSTRIAL TECHNOLOGY',
                    'COLLEGE OF INFORMATION AND COMPUTING SCIENCES',
                    'COLLEGE OF TEACHER EDUCATION',
                ]),
                'year_level' => fake()->randomElement(['1st Year', '2nd Year', '3rd Year', '4th Year', '5th Year']),
                'section' => fake()->randomElement(['A', 'B', 'C', '1A', '1B', '2A', '3B']),
            ];
        });
    }
}
