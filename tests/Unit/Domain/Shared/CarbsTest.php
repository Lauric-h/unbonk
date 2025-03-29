<?php

namespace App\Tests\Unit\Domain\Shared;

use App\Domain\Shared\Entity\Carbs;
use PHPUnit\Framework\TestCase;

final class CarbsTest extends TestCase
{
    public function testCannotInitializeWithNegativeValue(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Carbs cannot be negative: -1');

        new Carbs(-1);
    }

    public function testZeroValueInitializesWithDefaultValue(): void
    {
        $carbs = new Carbs(0);

        $this->assertSame(Carbs::DEFAULT_PER_HOUR, $carbs->value);
    }

    public function testCanInitializeWithPositiveValue(): void
    {
        $carbs = new Carbs(1);

        $this->assertSame(1, $carbs->value);
    }
}
