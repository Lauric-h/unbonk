<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\UpdateNutritionItemQuantity\UpdateNutritionItemQuantityCommand;
use App\Application\NutritionPlan\UseCase\UpdateNutritionItemQuantity\UpdateNutritionItemQuantityCommandHandler;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Entity\SegmentNutritionPlan;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Entity\Carbs;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class UpdateNutritionItemQuantityCommandHandlerTest extends TestCase
{
    public function testUpdateNutritionItemQuantityCommand(): void
    {
        $repository = $this->createMock(NutritionPlansCatalog::class);
        $handler = new UpdateNutritionItemQuantityCommandHandler($repository);

        $nutritionPlan = new NutritionPlanTestFixture()->build();
        $segmentPlan = $this->getFirstSegmentPlan($nutritionPlan);

        $nutritionItem = new NutritionItem(
            id: 'abcde',
            segmentNutritionPlan: $segmentPlan,
            foodItemId: 'externalReference',
            quantity: new Quantity(2),
            carbs: new Carbs(40),
        );
        $segmentPlan->addItem($nutritionItem);

        $repository->expects($this->once())
            ->method('get')
            ->with($nutritionPlan->id)
            ->willReturn($nutritionPlan);

        $repository->expects($this->once())
            ->method('add')
            ->with($nutritionPlan);

        ($handler)(new UpdateNutritionItemQuantityCommand($nutritionPlan->id, $segmentPlan->segment->id, 'abcde', 4));

        $this->assertSame(4, $nutritionItem->quantity->value);
    }

    public function testUpdateNutritionItemQuantityCommandWithZeroQuantityRemovesItem(): void
    {
        $repository = $this->createMock(NutritionPlansCatalog::class);
        $handler = new UpdateNutritionItemQuantityCommandHandler($repository);

        $nutritionPlan = new NutritionPlanTestFixture()->build();
        $segmentPlan = $this->getFirstSegmentPlan($nutritionPlan);

        $nutritionItem = new NutritionItem(
            id: 'abcde',
            segmentNutritionPlan: $segmentPlan,
            foodItemId: 'externalReference',
            quantity: new Quantity(2),
            carbs: new Carbs(40),
        );
        $segmentPlan->addItem($nutritionItem);

        $repository->expects($this->once())
            ->method('get')
            ->with($nutritionPlan->id)
            ->willReturn($nutritionPlan);

        $repository->expects($this->once())
            ->method('add')
            ->with($nutritionPlan);

        ($handler)(new UpdateNutritionItemQuantityCommand($nutritionPlan->id, $segmentPlan->segment->id, 'abcde', 0));

        $this->assertNull($segmentPlan->getItemById('abcde'));
    }

    public function testUpdateNutritionItemQuantityCommandWithUnknownItemThrowsException(): void
    {
        $repository = $this->createMock(NutritionPlansCatalog::class);
        $handler = new UpdateNutritionItemQuantityCommandHandler($repository);

        $nutritionPlan = new NutritionPlanTestFixture()->build();
        $segmentPlan = $this->getFirstSegmentPlan($nutritionPlan);

        $repository->expects($this->once())
            ->method('get')
            ->with($nutritionPlan->id)
            ->willReturn($nutritionPlan);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Nutrition item with id "abcde" not found');

        ($handler)(new UpdateNutritionItemQuantityCommand($nutritionPlan->id, $segmentPlan->segment->id, 'abcde', 1));
    }

    private function getFirstSegmentPlan(NutritionPlan $nutritionPlan): SegmentNutritionPlan
    {
        $segmentPlan = $nutritionPlan->getSegmentPlans()->first();
        $this->assertInstanceOf(SegmentNutritionPlan::class, $segmentPlan);

        return $segmentPlan;
    }
}
