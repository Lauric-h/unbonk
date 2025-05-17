<?php

namespace App\Tests\Unit\UI\Race;

use App\Application\Race\ReadModel\MetricsFromStartReadModel;
use App\Domain\Race\Entity\MetricsFromStart;
use PHPUnit\Framework\TestCase;

final class MetricsFromStartReadModelTest extends TestCase
{
    public function testFromDomain(): void
    {
        $metrics = MetricsFromStart::create(120, 10, 1000, 1000);
        $expected = new MetricsFromStartReadModel(120, 10, 1000, 1000);

        $actual = MetricsFromStartReadModel::fromDomain($metrics);

        $this->assertEquals($expected, $actual);
    }
}
