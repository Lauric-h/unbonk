<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\ReadModel\CheckpointReadModel;
use App\Application\NutritionPlan\ReadModel\NutritionItemReadModel;
use App\Application\NutritionPlan\ReadModel\NutritionPlanReadModel;
use App\Application\NutritionPlan\ReadModel\RunnerRaceReadModel;
use App\Application\NutritionPlan\ReadModel\SegmentNutritionPlanReadModel;
use App\Application\NutritionPlan\ReadModel\SegmentReadModel;
use App\Application\NutritionPlan\UseCase\GetNutritionPlan\GetNutritionPlanQuery;
use App\Application\NutritionPlan\UseCase\GetNutritionPlan\GetNutritionPlanQueryHandler;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Entity\SegmentNutritionPlan;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Entity\Carbs;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class GetNutritionPlanQueryHandlerTest extends TestCase
{
    public function testGetNutritionPlan(): void
    {
        $repository = $this->createMock(NutritionPlansCatalog::class);
        $handler = new GetNutritionPlanQueryHandler($repository);

        $nutritionPlan = new NutritionPlanTestFixture()->build();
        $id = $nutritionPlan->id;
        $query = new GetNutritionPlanQuery($id);

        $segmentPlan = $this->getFirstSegmentPlan($nutritionPlan);
        $segmentPlan->addItem(new NutritionItem(
            id: 'item-id',
            segmentNutritionPlan: $segmentPlan,
            foodItemId: 'externalRef',
            quantity: new Quantity(2),
            carbs: new Carbs(25),
        ));

        $repository->expects($this->once())
            ->method('get')
            ->with($id)
            ->willReturn($nutritionPlan);

        $result = ($handler)($query);

        $this->assertInstanceOf(NutritionPlanReadModel::class, $result);
        $this->assertSame($nutritionPlan->id, $result->id);
        $this->assertSame($nutritionPlan->runnerRace->runnerId, $result->runnerId);
        $this->assertInstanceOf(RunnerRaceReadModel::class, $result->runnerRace);
        $this->assertSame('external-race-id', $result->runnerRace->externalRaceId);
        $this->assertSame('external-event-id', $result->runnerRace->externalEventId);
        $this->assertSame('Test Event', $result->runnerRace->name);
        $this->assertCount(3, $result->runnerRace->checkpoints);
        $this->assertCount(2, $result->runnerRace->segments);
        $this->assertSame(25, $result->totalCarbs);
        $this->assertCount(2, $result->segmentPlans);

        $firstSegmentPlan = $result->segmentPlans[0];
        $this->assertInstanceOf(SegmentNutritionPlanReadModel::class, $firstSegmentPlan);
        $this->assertInstanceOf(SegmentReadModel::class, $firstSegmentPlan->segment);
        $this->assertInstanceOf(CheckpointReadModel::class, $firstSegmentPlan->segment->fromCheckpoint);
        $this->assertInstanceOf(CheckpointReadModel::class, $firstSegmentPlan->segment->toCheckpoint);
        $this->assertSame('start-checkpoint-id', $firstSegmentPlan->segment->fromCheckpoint->id);
        $this->assertSame('aid-station-id', $firstSegmentPlan->segment->toCheckpoint->id);
        $this->assertCount(1, $firstSegmentPlan->items);

        $nutritionItemReadModel = $firstSegmentPlan->items[0];
        $this->assertInstanceOf(NutritionItemReadModel::class, $nutritionItemReadModel);
        $this->assertSame('item-id', $nutritionItemReadModel->id);
        $this->assertSame('externalRef', $nutritionItemReadModel->foodItemId);
        $this->assertSame(2, $nutritionItemReadModel->quantity);
        $this->assertSame(25, $nutritionItemReadModel->carbs);
    }

    private function getFirstSegmentPlan(NutritionPlan $nutritionPlan): SegmentNutritionPlan
    {
        $segmentPlan = $nutritionPlan->getSegmentPlans()->first();
        $this->assertInstanceOf(SegmentNutritionPlan::class, $segmentPlan);

        return $segmentPlan;
    }
}
