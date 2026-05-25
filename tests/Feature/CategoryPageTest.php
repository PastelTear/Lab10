<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\CraftCategory;
use App\Models\Masterclass;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryPageTest extends TestCase
{
    #[Test]
    public function it_shows_category_with_masterclasses(): void
    {
        $category = CraftCategory::factory()->create(['name' => 'Кулинария']);
        $masterclass = Masterclass::factory()->create([
            'category_id' => $category->id,
            'title' => 'Пельмени мастер',
        ]);

        $this->get(route('category.show', $category))
            ->assertOk()
            ->assertSee('Кулинария')
            ->assertSee('Пельмени мастер')
            ->assertSee($masterclass->leader->fio);
    }

    #[Test]
    public function visitor_sees_book_button_when_places_available(): void
    {
        $visitor = User::factory()->visitor()->create();
        $masterclass = Masterclass::factory()->create(['max_participants' => 5]);

        $this->actingAs($visitor)
            ->get(route('category.show', $masterclass->category_id))
            ->assertOk()
            ->assertSee('записаться');
    }

    #[Test]
    public function guest_does_not_see_book_button(): void
    {
        $masterclass = Masterclass::factory()->create();

        $response = $this->get(route('category.show', $masterclass->category_id));

        $response->assertOk();
        $this->assertStringNotContainsString(
            route('book.confirm', $masterclass),
            $response->getContent()
        );
    }
}
