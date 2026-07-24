<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\DeleteNutritionItem\DeleteNutritionItemCommand;
use App\Application\NutritionPlan\UseCase\DeleteNutritionItem\DeleteNutritionItemCommandHandler;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Entity\SegmentNutritionPlan;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Entity\Carbs;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class DeleteNutritionCommandHandlerTest extends TestCase
{
    public function testDeleteNutritionItem(): void
    {
        $repository = $this->createMock(NutritionPlansCatalog::class);
        $handler = new DeleteNutritionItemCommandHandler($repository);

        $nutritionPlan = new NutritionPlanTestFixture()->build();
        $segmentPlan = $this->getFirstSegmentPlan($nutritionPlan);

        $nutritionPlanId = $nutritionPlan->id;
        $segmentId = $segmentPlan->segment->id;
        $nutritionItemId = 'nId';

        $nutritionItem = new NutritionItem(
            id: 'nId',
            segmentNutritionPlan: $segmentPlan,
            foodItemId: 'externalReference',
            quantity: new Quantity(2),
            carbs: new Carbs(40)
        );
        $segmentPlan->addItem($nutritionItem);

        $nutritionItem2 = new NutritionItem(
            id: 'fghij',
            segmentNutritionPlan: $segmentPlan,
            foodItemId: 'externalReference2',
            quantity: new Quantity(2),
            carbs: new Carbs(40)
        );
        $segmentPlan->addItem($nutritionItem2);

        $repository->expects($this->once())
            ->method('get')
            ->with($nutritionPlanId)
            ->willReturn($nutritionPlan);

        $repository->expects($this->once())
            ->method('add')
            ->with($nutritionPlan);

        $command = new DeleteNutritionItemCommand($nutritionPlanId, $segmentId, $nutritionItemId);
        ($handler)($command);

        $this->assertCount(1, $segmentPlan->getItems());
        $this->assertSame('fghij', $segmentPlan->getItems()->first()->id);
    }

    private function getFirstSegmentPlan(NutritionPlan $nutritionPlan): SegmentNutritionPlan
    {
        $segmentPlan = $nutritionPlan->getSegmentPlans()->first();
        $this->assertInstanceOf(SegmentNutritionPlan::class, $segmentPlan);

        return $segmentPlan;
    }
}
