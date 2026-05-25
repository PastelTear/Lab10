<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password123'),
            'fio' => fake()->name(),
            'phone' => '+79'.fake()->numerify('#########'),
            'role' => 'visitor',
            'remember_token' => Str::random(10),
        ];
    }

    public function leader(): static
    {
        return $this->state(fn (): array => ['role' => 'leader']);
    }

    public function visitor(): static
    {
        return $this->state(fn (): array => ['role' => 'visitor']);
    }
}
