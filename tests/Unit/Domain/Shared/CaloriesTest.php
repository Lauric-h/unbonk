<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Shared;

use App\Domain\Shared\Entity\Calories;
use PHPUnit\Framework\TestCase;

final class CaloriesTest extends TestCase
{
    public function testCannotInitializeWithNegativeValue(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Calories cannot be negative: -1');

        new Calories(-1);
    }

    public function testCanInitializeWithZeroValue(): void
    {
        $calories = new Calories(0);

        $this->assertSame(0, $calories->value);
    }

    public function testCanInitializeWithPositiveValue(): void
    {
        $calories = new Calories(1);

        $this->assertSame(1, $calories->value);
    }
}
