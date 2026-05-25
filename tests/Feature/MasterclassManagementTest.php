<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\CraftCategory;
use App\Models\Masterclass;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MasterclassManagementTest extends TestCase
{
    #[Test]
    public function leader_can_create_masterclass(): void
    {
        $leader = User::factory()->leader()->create();
        $category = CraftCategory::factory()->create();

        $this->actingAs($leader)
            ->post(route('masterclass.store'), [
                'category_id' => $category->id,
                'title' => 'Новый мастер-класс',
                'description' => 'Описание занятия',
                'mc_date' => now()->addWeek()->format('Y-m-d'),
                'start_time' => '11:00:00',
                'max_participants' => 8,
                'price' => 1500,
            ])
            ->assertRedirect(route('cabinet'))
            ->assertSessionHas('ok');

        $this->assertDatabaseHas('masterclasses', [
            'leader_id' => $leader->id,
            'title' => 'Новый мастер-класс',
            'start_time' => '11:00:00',
        ]);
    }

    #[Test]
    public function leader_cannot_create_masterclass_with_missing_fields(): void
    {
        $leader = User::factory()->leader()->create();

        $this->actingAs($leader)
            ->from(route('masterclass.create'))
            ->post(route('masterclass.store'), [])
            ->assertRedirect(route('masterclass.create'))
            ->assertSessionHasErrors(['category_id', 'title', 'description']);
    }

    #[Test]
    public function leader_can_update_description_and_price(): void
    {
        $leader = User::factory()->leader()->create();
        $masterclass = Masterclass::factory()->create([
            'leader_id' => $leader->id,
            'description' => 'Старое описание',
            'price' => 1000,
        ]);

        $this->actingAs($leader)
            ->put(route('masterclass.update', $masterclass), [
                'description' => 'Новое описание',
                'price' => 2500,
            ])
            ->assertRedirect(route('cabinet'));

        $masterclass->refresh();
        $this->assertSame('Новое описание', $masterclass->description);
        $this->assertEquals(2500.0, (float) $masterclass->price);
    }

    #[Test]
    public function leader_cannot_edit_another_leaders_masterclass(): void
    {
        $leader = User::factory()->leader()->create();
        $other = User::factory()->leader()->create();
        $masterclass = Masterclass::factory()->create(['leader_id' => $other->id]);

        $this->actingAs($leader)
            ->get(route('masterclass.edit', $masterclass))
            ->assertStatus(403);
    }

    #[Test]
    public function busy_slots_returns_leader_schedule_for_date(): void
    {
        $leader = User::factory()->leader()->create();
        Masterclass::factory()->create([
            'leader_id' => $leader->id,
            'mc_date' => '2026-07-01',
            'start_time' => '09:00:00',
        ]);

        $this->actingAs($leader)
            ->getJson(route('busy-slots', ['date' => '2026-07-01']))
            ->assertOk()
            ->assertJson(['busy' => ['09:00:00']]);
    }
}
