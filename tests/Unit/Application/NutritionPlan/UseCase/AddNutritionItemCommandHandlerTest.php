<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\AddNutritionItem\AddNutritionItemCommand;
use App\Application\NutritionPlan\UseCase\AddNutritionItem\AddNutritionItemCommandHandler;
use App\Domain\NutritionPlan\DTO\ExternalNutritionItemDTO;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\SegmentNutritionPlan;
use App\Domain\NutritionPlan\Port\ExternalFoodPort;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use App\Tests\Unit\MockIdGenerator;
use PHPUnit\Framework\TestCase;

final class AddNutritionItemCommandHandlerTest extends TestCase
{
    public function testAddNutritionItem(): void
    {
        $repository = $this->createMock(NutritionPlansCatalog::class);
        $idGenerator = new MockIdGenerator('abcde');
        $foodPort = $this->createMock(ExternalFoodPort::class);
        $handler = new AddNutritionItemCommandHandler($repository, $idGenerator, $foodPort);

        $nutritionPlan = new NutritionPlanTestFixture()->build();
        $segmentPlan = $this->getFirstSegmentPlan($nutritionPlan);

        $segmentId = $segmentPlan->segment->id;
        $nutritionPlanId = $nutritionPlan->id;
        $foodId = 'externalReference';
        $quantity = 2;

        $foodDTO = new ExternalNutritionItemDTO('externalReference', 'name', 40);
        $foodPort->expects($this->once())
            ->method('getById')
            ->with($foodId)
            ->willReturn($foodDTO);

        $repository->expects($this->once())
            ->method('get')
            ->with($nutritionPlanId)
            ->willReturn($nutritionPlan);

        $repository->expects($this->once())
            ->method('add')
            ->with($nutritionPlan);

        ($handler)(new AddNutritionItemCommand($foodId, $nutritionPlanId, $segmentId, $quantity));

        $this->assertCount(1, $segmentPlan->getItems());
        $nutritionItem = $segmentPlan->getItems()->first();
        $this->assertInstanceOf(NutritionItem::class, $nutritionItem);
        $this->assertSame('abcde', $nutritionItem->id);
        $this->assertSame($segmentPlan, $nutritionItem->segmentNutritionPlan);
        $this->assertSame('externalReference', $nutritionItem->foodItemId);
        $this->assertSame(40, $nutritionItem->carbs->value);
        $this->assertSame(2, $nutritionItem->quantity->value);
    }

    private function getFirstSegmentPlan(NutritionPlan $nutritionPlan): SegmentNutritionPlan
    {
        $segmentPlan = $nutritionPlan->getSegmentPlans()->first();
        $this->assertInstanceOf(SegmentNutritionPlan::class, $segmentPlan);

        return $segmentPlan;
    }
}
