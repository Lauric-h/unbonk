<?php

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\UpdateNutritionItemQuantity\UpdateNutritionItemQuantityCommand;
use App\Application\NutritionPlan\UseCase\UpdateNutritionItemQuantity\UpdateNutritionItemQuantityCommandHandler;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\NutritionPlan\Repository\SegmentsCatalog;
use App\Domain\Shared\Entity\Calories;
use App\Domain\Shared\Entity\Carbs;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class UpdateNutritionItemQuantityCommandHandlerTest extends TestCase
{
    public function testUpdateNutritionItemQuantityCommand(): void
    {
        $repository = $this->createMock(SegmentsCatalog::class);
        $handler = new UpdateNutritionItemQuantityCommandHandler($repository);

        $nutritionPlan = (new NutritionPlanTestFixture())->build();
        $segment = $nutritionPlan->getSegmentByPosition(1);
        $this->assertInstanceOf(Segment::class, $segment);

        $nutritionItem = new NutritionItem(
            id: 'abcde',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            calories: new Calories(0),
        );
        $segment->addNutritionItem($nutritionItem);

        $repository->expects($this->once())
            ->method('get')
            ->with($segment->id)
            ->willReturn($segment);

        $repository->expects($this->once())
            ->method('add');

        ($handler)(new UpdateNutritionItemQuantityCommand($segment->id, 'abcde', 4));

        $this->assertSame(4, $nutritionItem->quantity->value);
    }

    public function testUpdateNutritionItemQuantityCommandWithZeroQuantityRemovesItem(): void
    {
        $repository = $this->createMock(SegmentsCatalog::class);
        $handler = new UpdateNutritionItemQuantityCommandHandler($repository);

        $nutritionPlan = (new NutritionPlanTestFixture())->build();
        $segment = $nutritionPlan->getSegmentByPosition(1);
        $this->assertInstanceOf(Segment::class, $segment);

        $nutritionItem = new NutritionItem(
            id: 'abcde',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            calories: new Calories(0),
        );
        $segment->addNutritionItem($nutritionItem);

        $repository->expects($this->once())
            ->method('get')
            ->with($segment->id)
            ->willReturn($segment);

        $repository->expects($this->once())
            ->method('add');

        ($handler)(new UpdateNutritionItemQuantityCommand($segment->id, 'abcde', 0));

        $this->assertNotInstanceOf(NutritionItem::class, $segment->getNutritionItemById('abcde'));
    }

    public function testUpdateNutritionItemQuantityCommandWithUnknowItemThrowsException(): void
    {
        $repository = $this->createMock(SegmentsCatalog::class);
        $handler = new UpdateNutritionItemQuantityCommandHandler($repository);

        $nutritionPlan = (new NutritionPlanTestFixture())->build();
        $segment = $nutritionPlan->getSegmentByPosition(1);
        $this->assertInstanceOf(Segment::class, $segment);

        $repository->expects($this->once())
            ->method('get')
            ->with($segment->id)
            ->willReturn($segment);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Nutrition item with id "abcde" not found');

        ($handler)(new UpdateNutritionItemQuantityCommand($segment->id, 'abcde', 1));
    }
}
