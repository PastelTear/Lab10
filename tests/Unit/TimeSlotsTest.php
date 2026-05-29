<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\TimeSlots;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TimeSlotsTest extends TestCase
{
    #[Test]
    public function it_normalizes_time_to_slot_key(): void
    {
        $this->assertSame('09:00:00', TimeSlots::normalize('9:0'));
        $this->assertSame('11:00:00', TimeSlots::normalize('11:00'));
    }

    #[Test]
    public function it_returns_human_readable_slot_label(): void
    {
        $this->assertSame('9.00—11.00', TimeSlots::label('09:00:00'));
    }

    #[Test]
    public function it_validates_allowed_slots(): void
    {
        $this->assertTrue(TimeSlots::isValid('09:00:00'));
        $this->assertTrue(TimeSlots::isValid('10:00:00'));
    }
}
