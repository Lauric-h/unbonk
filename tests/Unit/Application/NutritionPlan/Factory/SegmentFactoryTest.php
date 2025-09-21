<?php

namespace App\Tests\Unit\Application\NutritionPlan\Factory;

use App\Application\NutritionPlan\Factory\SegmentFactory;
use App\Domain\NutritionPlan\Entity\SegmentPoint;
use App\Domain\Race\Entity\NutritionPlan;
use App\Domain\Race\Entity\Segment;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Carbs;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use App\Tests\Unit\MockIdGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class SegmentFactoryTest extends TestCase
{
    public function testCreateFromPoints(): void
    {
        $idGenerator = new MockIdGenerator('fakeId');
        $factory = new SegmentFactory($idGenerator);

        $startPoint = new SegmentPoint(
            'start',
            new Distance(10),
            new Duration(50),
            new Ascent(500),
            new Descent(500)
        );
        $finishPoint = new SegmentPoint(
            'finish',
            new Distance(20),
            new Duration(200),
            new Ascent(2000),
            new Descent(2000)
        );
        $nutritionPlan = new NutritionPlan('id', 'raceId', 'runnerId');

        $expected = new Segment(
            'fakeId',
            'start',
            'finish',
            new Distance(10),
            new Ascent(1500),
            new Descent(1500),
            new Duration(150),
            new Carbs(60),
            $nutritionPlan,
        );

        $actual = $factory->createFromPoints($startPoint, $finishPoint, $nutritionPlan);

        $this->assertEquals($expected, $actual);
    }

    public function testCreateWithNutritionData(): void
    {
        $idGenerator = new MockIdGenerator('fakeId');
        $factory = new SegmentFactory($idGenerator);

        $startPoint = new SegmentPoint(
            'start',
            new Distance(10),
            new Duration(50),
            new Ascent(500),
            new Descent(500)
        );
        $finishPoint = new SegmentPoint(
            'finish',
            new Distance(20),
            new Duration(200),
            new Ascent(2000),
            new Descent(2000)
        );

        $nutritionPlan = new NutritionPlan('id', 'raceId', 'runnerId');

        $carbs = new Carbs(120);

        $nutritionItems = new ArrayCollection([]);

        $expected = new Segment(
            'fakeId',
            'start',
            'finish',
            new Distance(10),
            new Ascent(1500),
            new Descent(1500),
            new Duration(150),
            new Carbs(120),
            $nutritionPlan,
            $nutritionItems,
        );

        $actual = $factory->createWithNutritionData($startPoint, $finishPoint, $carbs, $nutritionPlan, $nutritionItems);

        $this->assertEquals($expected, $actual);
    }
}
