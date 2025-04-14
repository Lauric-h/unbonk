<?php

namespace App\Tests\Unit\UI\NutritionPlan;

use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Carbs;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use App\UI\Http\Rest\NutritionPlan\View\NutritionItemReadModel;
use App\UI\Http\Rest\NutritionPlan\View\SegmentReadModel;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class SegmentReadModelTest extends TestCase
{
    public function testFromSegment(): void
    {
        $nutritionPlan = new NutritionPlan(
            'id',
            'raceId',
            'runnerId',
            new ArrayCollection([])
        );

        $segment = new Segment(
            id: 'segmentId',
            startId: 'startId',
            finishId: 'finishId',
            distance: new Distance(1),
            ascent: new Ascent(1),
            descent: new Descent(1),
            estimatedTimeInMinutes: new Duration(120),
            carbsTarget: new Carbs(0),
            nutritionPlan: $nutritionPlan
        );

        $nutritionItem = new NutritionItem(
            id: 'abcde',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            segment: $segment,
            calories: null
        );
        $segment->nutritionItems->add($nutritionItem);

        $expected = new SegmentReadModel(
            'segmentId',
            'startId',
            'finishId',
            1,
            1,
            1,
            120,
            60,
            [
                new NutritionItemReadModel(
                    id: 'abcde',
                    externalReference: 'externalReference',
                    name: 'name',
                    carbs: 40,
                    quantity: 2,
                    calories: null
                ),
            ],
        );

        $actual = SegmentReadModel::fromSegment($segment);

        $this->assertEquals($expected, $actual);
    }
}
