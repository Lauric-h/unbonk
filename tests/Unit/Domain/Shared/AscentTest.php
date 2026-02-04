<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Shared;

use App\Domain\Shared\Entity\Ascent;
use PHPUnit\Framework\TestCase;

final class AscentTest extends TestCase
{
    public function testCannotInitializeWithNegativeValue(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Ascent cannot be negative: -1');

        new Ascent(-1);
    }

    public function testCanInitializeWithZeroValue(): void
    {
        $ascent = new Ascent(0);

        $this->assertSame(0, $ascent->value);
    }

    public function testCanInitializeWithPositiveValue(): void
    {
        $ascent = new Ascent(1);

        $this->assertSame(1, $ascent->value);
    }
}
