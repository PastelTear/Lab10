<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CraftCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CraftCategory>
 */
class CraftCategoryFactory extends Factory
{
    protected $model = CraftCategory::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'image' => 'img/elifant.png',
        ];
    }
}
