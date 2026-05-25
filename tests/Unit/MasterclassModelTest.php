<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Masterclass;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MasterclassModelTest extends TestCase
{
    #[Test]
    public function it_calculates_free_places(): void
    {
        $masterclass = Masterclass::factory()->create(['max_participants' => 3]);
        Booking::factory()->count(2)->create(['masterclass_id' => $masterclass->id]);

        $this->assertSame(1, $masterclass->freePlaces());
        $this->assertSame(2, $masterclass->bookedCount());
    }
}
