<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CraftCategory;
use App\Models\Masterclass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Masterclass>
 */
class MasterclassFactory extends Factory
{
    protected $model = Masterclass::class;

    public function definition(): array
    {
        return [
            'leader_id' => User::factory()->leader(),
            'category_id' => CraftCategory::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'mc_date' => now()->addDays(7)->format('Y-m-d'),
            'start_time' => '09:00:00',
            'max_participants' => 10,
            'price' => fake()->randomFloat(2, 500, 5000),
        ];
    }
}
