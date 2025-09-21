<?php

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\UpdateNutritionItemQuantity\UpdateNutritionItemQuantityCommand;
use App\Application\NutritionPlan\UseCase\UpdateNutritionItemQuantity\UpdateNutritionItemQuantityCommandHandler;
use App\Domain\Race\Entity\NutritionItem;
use App\Domain\Race\Entity\NutritionPlan;
use App\Domain\Race\Entity\Quantity;
use App\Domain\Race\Entity\Segment;
use App\Domain\Race\Repository\SegmentsCatalog;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Calories;
use App\Domain\Shared\Entity\Carbs;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class UpdateNutritionItemQuantityCommandHandlerTest extends TestCase
{
    public function testUpdateNutritionItemQuantityCommand(): void
    {
        $repository = $this->createMock(SegmentsCatalog::class);
        $handler = new UpdateNutritionItemQuantityCommandHandler($repository);

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

        $nutritionItem = new NutritionItem(
            id: 'abcde',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            nutritionSegment: $segment,
            calories: new Calories(0),
        );
        $segment->nutritionItems->add($nutritionItem);

        $repository->expects($this->once())
            ->method('get')
            ->with('segmentId')
            ->willReturn($segment);

        $repository->expects($this->once())
            ->method('add');

        ($handler)(new UpdateNutritionItemQuantityCommand('segmentId', 'abcde', 4));

        $this->assertSame(4, $nutritionItem->quantity->value);
    }

    public function testUpdateNutritionItemQuantityCommandWithZeroQuantityRemovesItem(): void
    {
        $repository = $this->createMock(SegmentsCatalog::class);
        $handler = new UpdateNutritionItemQuantityCommandHandler($repository);

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

        $nutritionItem = new NutritionItem(
            id: 'abcde',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            nutritionSegment: $segment,
            calories: new Calories(0),
        );
        $segment->nutritionItems->add($nutritionItem);

        $repository->expects($this->once())
            ->method('get')
            ->with('segmentId')
            ->willReturn($segment);

        $repository->expects($this->once())
            ->method('add');

        ($handler)(new UpdateNutritionItemQuantityCommand('segmentId', 'abcde', 0));

        $this->assertNotInstanceOf(NutritionItem::class, $segment->getNutritionItemById('abcde'));
    }

    public function testUpdateNutritionItemQuantityCommandWithUnknowItemThrowsException(): void
    {
        $repository = $this->createMock(SegmentsCatalog::class);
        $handler = new UpdateNutritionItemQuantityCommandHandler($repository);

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

        $repository->expects($this->once())
            ->method('get')
            ->with('segmentId')
            ->willReturn($segment);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Nutrition item with id "abcde" not found');

        ($handler)(new UpdateNutritionItemQuantityCommand('segmentId', 'abcde', 1));
    }
}
