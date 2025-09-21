<?php

namespace App\Tests\Unit\Domain\NutritionPlan;

use App\Domain\Race\Entity\Quantity;
use PHPUnit\Framework\TestCase;

final class QuantityTest extends TestCase
{
    public function testCannotInitializeWithNegativeValue(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Quantity cannot be negative: -1');

        new Quantity(-1);
    }

    public function testCanInitializeWithZeroValue(): void
    {
        $quantity = new Quantity(0);

        $this->assertSame(0, $quantity->value);
    }

    public function testCanInitializeWithPositiveValue(): void
    {
        $quantity = new Quantity(1);

        $this->assertSame(1, $quantity->value);
    }
}
