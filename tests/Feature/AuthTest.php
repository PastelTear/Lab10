<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    #[Test]
    public function visitor_can_login_and_redirect_to_home(): void
    {
        $user = User::factory()->visitor()->create([
            'email' => 'visitor@test.com',
            'password' => 'password123',
        ]);

        $this->post(route('login'), [
            'email' => 'visitor@test.com',
            'password' => 'password123',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function leader_redirects_to_cabinet_after_login(): void
    {
        User::factory()->leader()->create([
            'email' => 'leader@test.com',
            'password' => 'password123',
        ]);

        $this->post(route('login'), [
            'email' => 'leader@test.com',
            'password' => 'password123',
        ])->assertRedirect(route('cabinet'));
    }

    #[Test]
    public function user_can_register_with_valid_data(): void
    {
        $this->post(route('register'), [
            'fio' => 'Тестов Тест Тестович',
            'email' => 'new@test.com',
            'password' => 'password123',
            'phone' => '+79161234567',
        ])->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'email' => 'new@test.com',
            'role' => 'visitor',
        ]);
    }

    #[Test]
    public function registration_rejects_invalid_phone(): void
    {
        $this->from(route('register'))
            ->post(route('register'), [
                'fio' => 'Тест',
                'email' => 'bad@test.com',
                'password' => 'password123',
                'phone' => '123',
            ])
            ->assertRedirect(route('register'))
            ->assertSessionHasErrors('phone');
    }

    #[Test]
    public function user_can_logout(): void
    {
        $user = User::factory()->visitor()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
