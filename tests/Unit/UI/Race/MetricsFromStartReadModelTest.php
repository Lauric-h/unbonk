<?php

namespace App\Tests\Unit\UI\Race;

use App\Domain\Race\Entity\MetricsFromStart;
use App\UI\Http\Rest\Race\View\MetricsFromStartReadModel;
use PHPUnit\Framework\TestCase;

final class MetricsFromStartReadModelTest extends TestCase
{
    public function testFromDomain(): void
    {
        $metrics = new MetricsFromStart(120, 10, 1000, 1000);
        $expected = new MetricsFromStartReadModel(120, 10, 1000, 1000);

        $actual = MetricsFromStartReadModel::fromDomain($metrics);

        $this->assertEquals($expected, $actual);
    }
}
