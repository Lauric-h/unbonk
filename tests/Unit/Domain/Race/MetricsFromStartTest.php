<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\MetricsFromStart;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MetricsFromStartTest extends TestCase
{
    #[DataProvider('provide')]
    public function testEquals(MetricsFromStart $oldMetrics, MetricsFromStart $newMetrics, bool $expected): void
    {
        $this->assertSame($expected, $oldMetrics->equals($newMetrics));
    }

    public static function provide(): \Generator
    {
        yield [
            'oldMetrics' => MetricsFromStart::create(1, 1, 1, 1),
            'newMetrics' => MetricsFromStart::create(1, 1, 1, 1),
            'expected' => true,
        ];
        yield [
            'oldMetrics' => MetricsFromStart::create(1, 1, 1, 1),
            'newMetrics' => MetricsFromStart::create(2, 1, 1, 1),
            'expected' => true,
        ];
        yield [
            'oldMetrics' => MetricsFromStart::create(1, 1, 1, 1),
            'newMetrics' => MetricsFromStart::create(2, 2, 2, 2),
            'expected' => false,
        ];
        yield [
            'oldMetrics' => MetricsFromStart::create(1, 1, 1, 1),
            'newMetrics' => MetricsFromStart::create(1, 2, 2, 2),
            'expected' => false,
        ];
    }
}
