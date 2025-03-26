<?php

namespace App\Tests\Unit\Domain\Shared;

use App\Domain\Shared\Entity\Distance;
use PHPUnit\Framework\TestCase;

final class DistanceTest extends TestCase
{
    public function testCannotInitializeWithNegativeValue(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Distance cannot be negative: -1');

        new Distance(-1);
    }

    public function testCanInitializeWithZeroValue(): void
    {
        $distance = new Distance(0);

        $this->assertSame(0, $distance->value);
    }

    public function testCanInitializeWithPositiveValue(): void
    {
        $distance = new Distance(1);

        $this->assertSame(1, $distance->value);
    }
}
