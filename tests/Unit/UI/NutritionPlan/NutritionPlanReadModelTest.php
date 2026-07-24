<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\NutritionPlan;

use App\Application\NutritionPlan\ReadModel\CheckpointReadModel;
use App\Application\NutritionPlan\ReadModel\NutritionPlanReadModel;
use App\Application\NutritionPlan\ReadModel\RunnerRaceReadModel;
use App\Application\NutritionPlan\ReadModel\SegmentNutritionPlanReadModel;
use App\Application\NutritionPlan\ReadModel\SegmentReadModel;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class NutritionPlanReadModelTest extends TestCase
{
    public function testFromNutritionPlan(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $actual = NutritionPlanReadModel::fromNutritionPlan($nutritionPlan);

        $this->assertSame($nutritionPlan->id, $actual->id);
        $this->assertNull($actual->name);
        $this->assertSame('runner-id', $actual->runnerId);
        $this->assertInstanceOf(RunnerRaceReadModel::class, $actual->runnerRace);
        $this->assertSame('runner-race-id', $actual->runnerRace->id);
        $this->assertSame('external-race-id', $actual->runnerRace->externalRaceId);
        $this->assertSame('external-event-id', $actual->runnerRace->externalEventId);
        $this->assertSame('Test Event', $actual->runnerRace->name);
        $this->assertCount(3, $actual->runnerRace->checkpoints);
        $this->assertCount(2, $actual->runnerRace->segments);
        $this->assertSame(0, $actual->totalCarbs);
        $this->assertCount(2, $actual->segmentPlans);

        $firstSegmentPlan = $actual->segmentPlans[0];
        $this->assertInstanceOf(SegmentNutritionPlanReadModel::class, $firstSegmentPlan);
        $this->assertInstanceOf(SegmentReadModel::class, $firstSegmentPlan->segment);
        $this->assertInstanceOf(CheckpointReadModel::class, $firstSegmentPlan->segment->fromCheckpoint);
        $this->assertInstanceOf(CheckpointReadModel::class, $firstSegmentPlan->segment->toCheckpoint);
        $this->assertSame('start-checkpoint-id', $firstSegmentPlan->segment->fromCheckpoint->id);
        $this->assertSame('aid-station-id', $firstSegmentPlan->segment->toCheckpoint->id);
        $this->assertCount(0, $firstSegmentPlan->items);
        $this->assertNull($firstSegmentPlan->targetCarbs);
    }
}
