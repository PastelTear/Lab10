<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Masterclass;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CabinetTest extends TestCase
{
    #[Test]
    public function leader_can_view_cabinet_with_own_masterclasses(): void
    {
        $leader = User::factory()->leader()->create(['fio' => 'Иванова Ольга']);
        $masterclass = Masterclass::factory()->create([
            'leader_id' => $leader->id,
            'title' => 'Резьба для начинающих',
        ]);
        $participant = User::factory()->visitor()->create(['fio' => 'Петров Пётр']);
        Booking::factory()->create([
            'masterclass_id' => $masterclass->id,
            'user_id' => $participant->id,
        ]);

        $this->actingAs($leader)
            ->get(route('cabinet'))
            ->assertOk()
            ->assertSee('Иванова Ольга')
            ->assertSee('Резьба для начинающих')
            ->assertSee('Петров Пётр');
    }

    #[Test]
    public function visitor_cannot_access_cabinet(): void
    {
        $visitor = User::factory()->visitor()->create();

        $this->actingAs($visitor)
            ->get(route('cabinet'))
            ->assertRedirect(route('home'));
    }

    #[Test]
    public function guest_is_redirected_to_login(): void
    {
        $this->get(route('cabinet'))
            ->assertRedirect(route('login'));
    }
}
