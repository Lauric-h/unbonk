<?php

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\AddNutritionItem\AddNutritionItemCommand;
use App\Application\NutritionPlan\UseCase\AddNutritionItem\AddNutritionItemCommandHandler;
use App\Domain\NutritionPlan\DTO\ExternalNutritionItemDTO;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\NutritionPlan\Port\ExternalFoodPort;
use App\Domain\NutritionPlan\Repository\SegmentsCatalog;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Calories;
use App\Domain\Shared\Entity\Carbs;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use App\Tests\Unit\MockIdGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class AddNutritionItemCommandHandlerTest extends TestCase
{
    public function testAddNutritionItem(): void
    {
        $repository = $this->createMock(SegmentsCatalog::class);
        $idGenerator = new MockIdGenerator('abcde');
        $foodPort = $this->createMock(ExternalFoodPort::class);
        $handler = new AddNutritionItemCommandHandler($repository, $idGenerator, $foodPort);

        $segmentId = 'segmentId';
        $nutritionPlanId = 'nutritionPlanId';
        $foodId = 'externalReference';
        $quantity = 2;

        $nutritionPlan = new NutritionPlan(
            'nutritionPlanId',
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

        $expectedSegment = new Segment(
            id: 'segmentId',
            startId: 'startId',
            finishId: 'finishId',
            distance: new Distance(1),
            ascent: new Ascent(1),
            descent: new Descent(1),
            estimatedTimeInMinutes: new Duration(120),
            carbsTarget: new Carbs(0),
            nutritionPlan: $nutritionPlan,
        );

        $nutritionItem = new NutritionItem(
            id: 'abcde',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            segment: $expectedSegment,
            calories: new Calories(0),
        );
        $expectedSegment->nutritionItems->add($nutritionItem);

        $foodDTO = new ExternalNutritionItemDTO('externalReference', 'name', 40);
        $foodPort->expects($this->once())
            ->method('getById')
            ->with($foodId)
            ->willReturn($foodDTO);

        $repository->expects($this->once())
            ->method('get')
            ->with($segmentId)
            ->willReturn($segment);

        $repository->expects($this->once())
            ->method('add')
            ->with($expectedSegment);

        ($handler)(new AddNutritionItemCommand($foodId, $nutritionPlanId, $segmentId, $quantity));
    }
}
