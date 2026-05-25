<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\CraftCategory;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    #[Test]
    public function guest_can_view_home_page(): void
    {
        CraftCategory::factory()->create();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Добро пожаловать');
    }

    #[Test]
    public function visitor_sees_bookings_on_home_page(): void
    {
        $visitor = User::factory()->visitor()->create();
        $booking = Booking::factory()->create(['user_id' => $visitor->id]);

        $this->actingAs($visitor)
            ->get(route('home'))
            ->assertOk()
            ->assertSee($booking->masterclass->title);
    }
}
