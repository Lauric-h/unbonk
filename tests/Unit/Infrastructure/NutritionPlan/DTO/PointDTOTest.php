<?php

namespace App\Tests\Unit\Infrastructure\NutritionPlan\DTO;

use App\Application\NutritionPlan\DTO\PointDTO;
use App\Domain\NutritionPlan\Entity\SegmentPoint;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use PHPUnit\Framework\TestCase;

final class PointDTOTest extends TestCase
{
    public function testToSegmentPoint(): void
    {
        $pointDTO = new PointDTO(
            'externalRef',
            10,
            100,
            1000,
            1000
        );

        $expected = new SegmentPoint(
            'externalRef',
            new Distance(10),
            new Duration(100),
            new Ascent(1000),
            new Descent(1000)
        );

        $actual = $pointDTO->toSegmentPoint();

        $this->assertEquals($expected, $actual);
    }

    public function testFromArray(): void
    {
        $array = [
            'id' => 'externalRef',
            'distance' => '10',
            'estimatedDuration' => '100',
            'ascent' => '1000',
            'descent' => '1000',
        ];

        $expected = new PointDTO(
            'externalRef',
            10,
            100,
            1000,
            1000
        );

        $actual = PointDTO::fromArray($array);

        $this->assertEquals($expected, $actual);
    }
}
