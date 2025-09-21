<?php

namespace App\Tests\Unit\UI\NutritionPlan;

use App\Application\NutritionPlan\ReadModel\NutritionItemReadModel;
use App\Domain\Race\Entity\NutritionItem;
use App\Domain\Race\Entity\NutritionPlan;
use App\Domain\Race\Entity\Quantity;
use App\Domain\Race\Entity\Segment;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Calories;
use App\Domain\Shared\Entity\Carbs;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class NutritionItemReadModelTest extends TestCase
{
    public function testFromNutritionItem(): void
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
            'id',
            'externalRef',
            'name',
            new Carbs(100),
            new Quantity(2),
            $segment,
            new Calories(300)
        );

        $expected = new NutritionItemReadModel(
            'id',
            'externalRef',
            'name',
            100,
            2,
            300
        );

        $actual = NutritionItemReadModel::fromNutritionItem($nutritionItem);

        $this->assertEquals($expected, $actual);
    }
}
