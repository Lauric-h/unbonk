<?php

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\GetNutritionPlan\GetNutritionPlanQuery;
use App\Application\NutritionPlan\UseCase\GetNutritionPlan\GetNutritionPlanQueryHandler;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Calories;
use App\Domain\Shared\Entity\Carbs;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use App\UI\Http\Rest\NutritionPlan\View\NutritionItemReadModel;
use App\UI\Http\Rest\NutritionPlan\View\NutritionPlanReadModel;
use App\UI\Http\Rest\NutritionPlan\View\SegmentReadModel;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class GetNutritionPlanQueryHandlerTest extends TestCase
{
    public function testGetNutritionPlan(): void
    {
        $id = 'id';
        $repository = $this->createMock(NutritionPlansCatalog::class);
        $handler = new GetNutritionPlanQueryHandler($repository);
        $query = new GetNutritionPlanQuery($id);

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
        $nutritionPlan->segments->add($segment);

        $nutritionItem = new NutritionItem(
            'id',
            'externalRef',
            'name',
            new Carbs(100),
            new Quantity(2),
            $segment,
            new Calories(300)
        );
        $segment->addNutritionItem($nutritionItem);

        $expected = new NutritionPlanReadModel(
            id: 'id',
            raceId: 'raceId',
            runnerId: 'runnerId',
            segments: [
                new SegmentReadModel(
                    id: 'segmentId',
                    startId: 'startId',
                    finishId: 'finishId',
                    distance: 1,
                    ascent: 1,
                    descent: 1,
                    estimatedTimeInMinutes: 120,
                    carbsTarget: 60,
                    nutritionItems: [
                        new NutritionItemReadModel(
                            'id',
                            'externalRef',
                            'name',
                            100,
                            2,
                            300
                        ),
                    ]
                ),
            ]
        );

        $repository->expects($this->once())
            ->method('get')
            ->with($id)
            ->willReturn($nutritionPlan);

        $actual = ($handler)($query);

        $this->assertEquals($expected, $actual);
    }
}
