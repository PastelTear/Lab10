<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\DateFormatter;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DateFormatterTest extends TestCase
{
    #[Test]
    public function it_formats_date_in_russian(): void
    {
        $formatted = DateFormatter::formatRu('2026-06-05');

        $this->assertStringContainsString('5', $formatted);
        $this->assertStringContainsString('июня', $formatted);
        $this->assertStringContainsString('2026', $formatted);
    }
}
