<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    #[Test]
    public function it_detects_leader_and_visitor_roles(): void
    {
        $leader = User::factory()->leader()->make();
        $visitor = User::factory()->visitor()->make();

        $this->assertTrue($leader->isLeader());
        $this->assertFalse($leader->isVisitor());
        $this->assertTrue($visitor->isVisitor());
        $this->assertFalse($visitor->isLeader());
    }
}
