<?php

namespace App\Tests\Unit\Domain\Shared;

use App\Domain\Shared\Entity\Duration;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('provideDuration')]
    public function testHours(int $minutes, float $hours): void
    {
        $duration = new Duration($minutes);
        $this->assertSame($hours, $duration->hours());
    }

    public static function provideDuration(): \Generator
    {
        yield [
            'minutes' => 0,
            'hours' => 0,
        ];
        yield [
            'minutes' => 60,
            'hours' => 1,
        ];
        yield [
            'minutes' => 90,
            'hours' => 1.5,
        ];
        yield [
            'minutes' => 120,
            'hours' => 2,
        ];
        yield [
            'minutes' => 1050,
            'hours' => 17.5,
        ];
    }
}
