<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\NutritionPlan;

use App\Application\NutritionPlan\ReadModel\CheckpointReadModel;
use App\Application\NutritionPlan\ReadModel\SegmentReadModel;
use App\Domain\NutritionPlan\Entity\Segment;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class SegmentReadModelTest extends TestCase
{
    public function testFromSegment(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();
        $segment = $nutritionPlan->runnerRace->segments()->first();

        $this->assertInstanceOf(Segment::class, $segment);

        $actual = SegmentReadModel::fromSegment($segment);

        $this->assertSame($segment->id, $actual->id);
        $this->assertSame($segment->position, $actual->position);
        $this->assertInstanceOf(CheckpointReadModel::class, $actual->fromCheckpoint);
        $this->assertSame('start-checkpoint-id', $actual->fromCheckpoint->id);
        $this->assertSame('start', $actual->fromCheckpoint->externalId);
        $this->assertSame('Start', $actual->fromCheckpoint->name);
        $this->assertInstanceOf(CheckpointReadModel::class, $actual->toCheckpoint);
        $this->assertSame('aid-station-id', $actual->toCheckpoint->id);
        $this->assertSame('aid-1', $actual->toCheckpoint->externalId);
        $this->assertSame(25000, $actual->distance);
        $this->assertSame(1000, $actual->ascent);
        $this->assertSame(750, $actual->descent);
    }
}
