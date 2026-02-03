<?php

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\DeleteNutritionItem\DeleteNutritionItemCommand;
use App\Application\NutritionPlan\UseCase\DeleteNutritionItem\DeleteNutritionItemCommandHandler;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Repository\SegmentsCatalog;
use App\Domain\Shared\Entity\Carbs;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class DeleteNutritionCommandHandlerTest extends TestCase
{
    public function testDeleteNutritionItem(): void
    {
        $repository = $this->createMock(SegmentsCatalog::class);
        $handler = new DeleteNutritionItemCommandHandler($repository);

        $nutritionPlan = (new NutritionPlanTestFixture())->build();
        $segment = $nutritionPlan->getSegmentByPosition(1);
        $this->assertInstanceOf(\App\Domain\NutritionPlan\Entity\Segment::class, $segment);

        $nutritionPlanId = $nutritionPlan->id;
        $segmentId = $segment->id;
        $nutritionItemId = 'nId';

        $nutritionItem = new NutritionItem(
            id: 'nId',
            externalReference: 'externalReference',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            calories: null
        );
        $segment->addNutritionItem($nutritionItem);

        $nutritionItem2 = new NutritionItem(
            id: 'fghij',
            externalReference: 'externalReference2',
            name: 'name',
            carbs: new Carbs(40),
            quantity: new Quantity(2),
            calories: null
        );
        $segment->addNutritionItem($nutritionItem2);

        $repository->expects($this->once())
            ->method('getByNutritionPlanAndId')
            ->with($nutritionPlanId, $segmentId)
            ->willReturn($segment);

        $repository->expects($this->once())
            ->method('add');

        $command = new DeleteNutritionItemCommand($nutritionPlanId, $segmentId, $nutritionItemId);
        ($handler)($command);

        $this->assertCount(1, $segment->getNutritionItems());
        $this->assertEquals('fghij', $segment->getNutritionItems()->first()->id);
    }
}
