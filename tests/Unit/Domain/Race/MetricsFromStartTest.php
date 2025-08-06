<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MetricsFromStartTest extends TestCase
{
    public function testCreate(): void
    {
        $metrics = MetricsFromStart::create(new Duration(1), new Distance(1), new Ascent(1), new Descent(1));

        $this->assertSame(1, $metrics->ascent);
        $this->assertSame(1, $metrics->descent);
        $this->assertSame(1, $metrics->distance);
        $this->assertSame(1, $metrics->estimatedTimeInMinutes);
    }

    #[DataProvider('provide')]
    public function testEquals(MetricsFromStart $oldMetrics, MetricsFromStart $newMetrics, bool $expected): void
    {
        $this->assertSame($expected, $oldMetrics->equals($newMetrics));
    }

    public static function provide(): \Generator
    {
        yield [
            'oldMetrics' => MetricsFromStart::create(new Duration(1), new Distance(1), new Ascent(1), new Descent(1)),
            'newMetrics' => MetricsFromStart::create(new Duration(1), new Distance(1), new Ascent(1), new Descent(1)),
            'expected' => true,
        ];
        yield [
            'oldMetrics' => MetricsFromStart::create(new Duration(1), new Distance(1), new Ascent(1), new Descent(1)),
            'newMetrics' => MetricsFromStart::create(new Duration(2), new Distance(1), new Ascent(1), new Descent(1)),
            'expected' => true,
        ];
        yield [
            'oldMetrics' => MetricsFromStart::create(new Duration(1), new Distance(1), new Ascent(1), new Descent(1)),
            'newMetrics' => MetricsFromStart::create(new Duration(2), new Distance(2), new Ascent(2), new Descent(2)),
            'expected' => false,
        ];
        yield [
            'oldMetrics' => MetricsFromStart::create(new Duration(1), new Distance(1), new Ascent(1), new Descent(1)),
            'newMetrics' => MetricsFromStart::create(new Duration(1), new Distance(2), new Ascent(2), new Descent(2)),
            'expected' => false,
        ];
    }
}
