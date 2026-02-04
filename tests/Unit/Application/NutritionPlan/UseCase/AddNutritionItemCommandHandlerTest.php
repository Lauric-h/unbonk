<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\AddNutritionItem\AddNutritionItemCommand;
use App\Application\NutritionPlan\UseCase\AddNutritionItem\AddNutritionItemCommandHandler;
use App\Domain\NutritionPlan\DTO\ExternalNutritionItemDTO;
use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\NutritionPlan\Port\ExternalFoodPort;
use App\Domain\NutritionPlan\Repository\SegmentsCatalog;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use App\Tests\Unit\MockIdGenerator;
use PHPUnit\Framework\TestCase;

final class AddNutritionItemCommandHandlerTest extends TestCase
{
    public function testAddNutritionItem(): void
    {
        $repository = $this->createMock(SegmentsCatalog::class);
        $idGenerator = new MockIdGenerator('abcde');
        $foodPort = $this->createMock(ExternalFoodPort::class);
        $handler = new AddNutritionItemCommandHandler($repository, $idGenerator, $foodPort);

        $nutritionPlan = new NutritionPlanTestFixture()->build();
        $segment = $nutritionPlan->getSegmentByPosition(1);
        $this->assertInstanceOf(Segment::class, $segment);

        $segmentId = $segment->id;
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
            ->with($segmentId)
            ->willReturn($segment);

        $repository->expects($this->once())
            ->method('add')
            ->with($segment);

        ($handler)(new AddNutritionItemCommand($foodId, $nutritionPlanId, $segmentId, $quantity));

        // Verify the nutrition item was added
        $this->assertCount(1, $segment->getNutritionItems());
        $nutritionItem = $segment->getNutritionItems()->first();
        $this->assertEquals('abcde', $nutritionItem->id);
        $this->assertEquals('externalReference', $nutritionItem->externalReference);
        $this->assertEquals('name', $nutritionItem->name);
        $this->assertEquals(40, $nutritionItem->carbs->value);
        $this->assertEquals(2, $nutritionItem->quantity->value);
    }
}
