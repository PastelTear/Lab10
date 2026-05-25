<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Masterclass;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    #[Test]
    public function visitor_can_confirm_booking(): void
    {
        $visitor = User::factory()->visitor()->create();
        $masterclass = Masterclass::factory()->create(['max_participants' => 2]);

        $this->actingAs($visitor)
            ->get(route('book.confirm', $masterclass))
            ->assertOk()
            ->assertSee($visitor->fio);

        $this->actingAs($visitor)
            ->post(route('book.store', $masterclass), ['action' => 'confirm'])
            ->assertRedirect(route('category.show', $masterclass->category_id))
            ->assertSessionHas('ok');

        $this->assertDatabaseHas('bookings', [
            'user_id' => $visitor->id,
            'masterclass_id' => $masterclass->id,
        ]);
    }

    #[Test]
    public function booking_cancel_shows_message_without_creating_record(): void
    {
        $visitor = User::factory()->visitor()->create();
        $masterclass = Masterclass::factory()->create();

        $this->actingAs($visitor)
            ->post(route('book.store', $masterclass), ['action' => 'cancel'])
            ->assertRedirect(route('category.show', $masterclass->category_id))
            ->assertSessionHas('ok');

        $this->assertDatabaseCount('bookings', 0);
    }

    #[Test]
    public function visitor_cannot_book_twice(): void
    {
        $visitor = User::factory()->visitor()->create();
        $masterclass = Masterclass::factory()->create(['max_participants' => 5]);
        Booking::factory()->create([
            'user_id' => $visitor->id,
            'masterclass_id' => $masterclass->id,
        ]);

        $this->actingAs($visitor)
            ->get(route('book.confirm', $masterclass))
            ->assertRedirect(route('category.show', $masterclass->category_id))
            ->assertSessionHas('error');
    }

    #[Test]
    public function leader_cannot_access_booking_routes(): void
    {
        $leader = User::factory()->leader()->create();
        $masterclass = Masterclass::factory()->create(['leader_id' => $leader->id]);

        $this->actingAs($leader)
            ->get(route('book.confirm', $masterclass))
            ->assertRedirect(route('home'));
    }
}
