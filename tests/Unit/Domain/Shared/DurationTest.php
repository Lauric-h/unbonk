<?php

namespace App\Tests\Unit\Domain\Shared;

use App\Domain\Shared\Entity\Duration;
use PHPUnit\Framework\TestCase;

final class DurationTest extends TestCase
{
    public function testCannotInitializeWithNegativeValue(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Minutes cannot be negative: -1');

        new Duration(-1);
    }

    public function testCanInitializeWithZeroValue(): void
    {
        $duration = new Duration(0);

        $this->assertSame(0, $duration->minutes);
    }

    public function testCanInitializeWithPositiveValue(): void
    {
        $duration = new Duration(1);

        $this->assertSame(1, $duration->minutes);
    }
}
