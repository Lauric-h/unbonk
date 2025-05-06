<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\MetricsFromStart;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MetricsFromStartTest extends TestCase
{
    #[DataProvider('provide')]
    public function testEquals(MetricsFromStart $metrics, bool $expected): void
    {

    }

    public static function provide(): \Generator
    {

    }
}