<?php

namespace App\Tests\Unit\Domain\NutritionPlan;

use App\Domain\Race\Entity\NutritionPlan;
use App\Domain\Race\Entity\Segment;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Carbs;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class NutritionPlanTest extends TestCase
{
    public function testGetSegmentByStartId(): void
    {
        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $segment1 = new Segment(
            'id',
            'startId',
            'finishId',
            new Distance(1),
            new Ascent(1),
            new Descent(1),
            new Duration(1),
            new Carbs(1),
            $nutritionPlan,
        );
        $segment2 = new Segment(
            'id2',
            'startId2',
            'finishId2',
            new Distance(2),
            new Ascent(2),
            new Descent(2),
            new Duration(2),
            new Carbs(2),
            $nutritionPlan,
        );

        $nutritionPlan->segments->add($segment1);
        $nutritionPlan->segments->add($segment2);

        $actual = $nutritionPlan->getSegmentByStartId('startId2');

        $this->assertSame($segment2, $actual);
    }

    public function testGetSegmentByStartIdReturnsNull(): void
    {
        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $segment1 = new Segment(
            'id',
            'startId',
            'finishId',
            new Distance(1),
            new Ascent(1),
            new Descent(1),
            new Duration(1),
            new Carbs(1),
            $nutritionPlan,
        );

        $nutritionPlan->segments->add($segment1);

        $actual = $nutritionPlan->getSegmentByStartId('startId2');

        $this->assertNotInstanceOf(Segment::class, $actual);
    }

    public function testReplaceAllSegments(): void
    {
        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $segment1 = new Segment(
            'id',
            'startId',
            'finishId',
            new Distance(1),
            new Ascent(1),
            new Descent(1),
            new Duration(1),
            new Carbs(1),
            $nutritionPlan,
        );
        $segment2 = new Segment(
            'id2',
            'startId2',
            'finishId2',
            new Distance(2),
            new Ascent(2),
            new Descent(2),
            new Duration(2),
            new Carbs(2),
            $nutritionPlan,
        );

        $nutritionPlan->segments->add($segment1);
        $nutritionPlan->segments->add($segment2);

        $segment3 = new Segment(
            'id',
            'startId',
            'finishId',
            new Distance(3),
            new Ascent(3),
            new Descent(3),
            new Duration(3),
            new Carbs(3),
            $nutritionPlan,
        );
        $segment4 = new Segment(
            'id4',
            'startId4',
            'finishId4',
            new Distance(4),
            new Ascent(4),
            new Descent(4),
            new Duration(4),
            new Carbs(4),
            $nutritionPlan,
        );

        $replacingSegments = new ArrayCollection([$segment3, $segment4]);

        $nutritionPlan->replaceAllSegments($replacingSegments);

        $this->assertCount(2, $nutritionPlan->segments);
        $this->assertEquals($replacingSegments, $nutritionPlan->segments);
    }
}
