<?php

namespace App\Tests\Unit\UI\Race;

use App\Application\Race\ReadModel\MetricsFromStartReadModel;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use PHPUnit\Framework\TestCase;

final class MetricsFromStartReadModelTest extends TestCase
{
    public function testFromDomain(): void
    {
        $metrics = MetricsFromStart::create(new Duration(120), new Distance(10), new Ascent(1000), new Descent(1000));
        $expected = new MetricsFromStartReadModel(120, 10, 1000, 1000);

        $actual = MetricsFromStartReadModel::fromDomain($metrics);

        $this->assertEquals($expected, $actual);
    }
}
