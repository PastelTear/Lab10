<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CraftCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        CraftCategory::query()->insert([
            [
                'name' => 'Архитектурное моделирование',
                'slug' => 'arch',
                'description' => 'Архитектурное моделирование — изготовление моделей зданий и сооружений. Программа расширяет творческое пространство.',
                'image' => 'img/elifant.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Кулинария',
                'slug' => 'cook',
                'description' => 'Кулинарные мастер-классы для любителей и начинающих поваров.',
                'image' => 'img/elifant.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Резьба по дереву',
                'slug' => 'wood',
                'description' => 'Основы резьбы по дереву и работа с инструментом.',
                'image' => 'img/elifant.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        User::query()->create([
            'email' => 'vedushiy@example.com',
            'password' => 'password123',
            'fio' => 'Иванова Ольга Ивановна',
            'phone' => '+79161234567',
            'role' => 'leader',
        ]);

        User::query()->create([
            'email' => 'gost@example.com',
            'password' => 'password123',
            'fio' => 'Иванов Иван Иванович',
            'phone' => '+79161234568',
            'role' => 'visitor',
        ]);
    }
}
