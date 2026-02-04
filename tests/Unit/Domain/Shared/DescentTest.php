<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Shared;

use App\Domain\Shared\Entity\Descent;
use PHPUnit\Framework\TestCase;

final class DescentTest extends TestCase
{
    public function testCannotInitializeWithNegativeValue(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Descent cannot be negative: -1');

        new Descent(-1);
    }

    public function testCanInitializeWithZeroValue(): void
    {
        $descent = new Descent(0);

        $this->assertSame(0, $descent->value);
    }

    public function testCanInitializeWithPositiveValue(): void
    {
        $descent = new Descent(1);

        $this->assertSame(1, $descent->value);
    }
}
