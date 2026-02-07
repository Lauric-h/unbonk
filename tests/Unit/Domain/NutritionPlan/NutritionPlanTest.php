<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\NutritionPlan;

use App\Domain\NutritionPlan\Entity\Checkpoint;
use App\Domain\NutritionPlan\Entity\CheckpointType;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Entity\Segment;
use App\Domain\Shared\Entity\Carbs;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class NutritionPlanTest extends TestCase
{
    public function testCreateFromImportedRaceCreatesSegments(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        // Default imported race has 3 checkpoints (Start, Aid Station, Finish)
        // So we should have 2 segments
        $this->assertCount(2, $nutritionPlan->getSegments());
    }

    public function testGetSegmentById(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        // Fixture generates segment IDs like 'segment-id-1', 'segment-id-2', etc.
        $segment = $nutritionPlan->getSegmentById('segment-id-1');

        $this->assertInstanceOf(Segment::class, $segment);
    }

    public function testGetSegmentByIdReturnsNull(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $segment = $nutritionPlan->getSegmentById('non-existent-id');

        $this->assertNotInstanceOf(Segment::class, $segment);
    }

    public function testGetSegmentByPosition(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $segment = $nutritionPlan->getSegmentByPosition(1);

        $this->assertInstanceOf(Segment::class, $segment);
        $this->assertSame(1, $segment->position);
    }

    public function testGetSegmentByPositionReturnsNull(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $segment = $nutritionPlan->getSegmentByPosition(99);

        $this->assertNotInstanceOf(Segment::class, $segment);
    }

    public function testAddCustomCheckpointCreatesNewSegment(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $initialSegmentCount = $nutritionPlan->getSegments()->count();

        $customCheckpoint = new Checkpoint(
            id: 'custom-checkpoint-id',
            externalId: null, // Custom checkpoints have null externalId
            name: 'Custom Point',
            location: 'Custom Location',
            distanceFromStart: 10000, // Somewhere between start (0) and aid station (25000)
            ascentFromStart: 500,
            descentFromStart: 400,
            cutoff: null,
            assistanceAllowed: true,
            importedRace: $nutritionPlan->race,
            type: CheckpointType::Intermediate
        );

        // After adding checkpoint, we'll have 4 checkpoints = 3 segments
        $nutritionPlan->addCustomCheckpoint($customCheckpoint, ['seg-1', 'seg-2', 'seg-3']);

        $this->assertCount($initialSegmentCount + 1, $nutritionPlan->getSegments());
    }

    public function testAddCustomCheckpointThrowsExceptionForNonCustomCheckpoint(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $nonCustomCheckpoint = new Checkpoint(
            'checkpoint-id',
            'external-id', // Non-null = not custom
            'Point',
            'Location',
            10000,
            500,
            400,
            null,
            true,
            $nutritionPlan->race,
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Checkpoint must be custom');

        $nutritionPlan->addCustomCheckpoint($nonCustomCheckpoint, ['seg-1', 'seg-2', 'seg-3']);
    }

    public function testAddCustomCheckpointThrowsExceptionForDuplicateDistance(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $customCheckpoint = new Checkpoint(
            id: 'custom-checkpoint-id',
            externalId: null,
            name: 'Custom Point',
            location: 'Custom Location',
            distanceFromStart: 25000, // Same distance as existing aid station
            ascentFromStart: 1000,
            descentFromStart: 750,
            cutoff: null,
            assistanceAllowed: true,
            importedRace: $nutritionPlan->race,
            type: CheckpointType::Intermediate
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('A checkpoint already exists at distance');

        $nutritionPlan->addCustomCheckpoint($customCheckpoint, ['seg-1', 'seg-2', 'seg-3']);
    }

    public function testRemoveCustomCheckpointRemovesSegment(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        // First add a custom checkpoint (INTERMEDIATE type)
        $customCheckpoint = new Checkpoint(
            'custom-checkpoint-id',
            null,
            'Custom Point',
            'Custom Location',
            10000,
            500,
            400,
            null,
            true,
            $nutritionPlan->race,
            CheckpointType::Intermediate,
        );
        $nutritionPlan->addCustomCheckpoint($customCheckpoint, ['seg-1', 'seg-2', 'seg-3']);

        $segmentCountAfterAdd = $nutritionPlan->getSegments()->count();

        // Now remove it - back to 3 checkpoints = 2 segments
        $nutritionPlan->removeCheckpoint('custom-checkpoint-id', ['seg-a', 'seg-b']);

        $this->assertCount($segmentCountAfterAdd - 1, $nutritionPlan->getSegments());
    }

    public function testRemoveCustomCheckpointThrowsExceptionForNonExistentCheckpoint(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Checkpoint with id non-existent not found');

        $nutritionPlan->removeCheckpoint('non-existent', ['seg-1', 'seg-2']);
    }

    public function testRemoveCustomCheckpointThrowsExceptionForImportedCheckpoint(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot remove a non-editable checkpoint');

        // Try to remove an imported checkpoint (start)
        $nutritionPlan->removeCheckpoint('start-checkpoint-id', ['seg-1', 'seg-2']);
    }

    public function testRebuildSegmentsPreservesNutritionItems(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        // Add nutrition item to first segment
        $segment = $nutritionPlan->getSegmentByPosition(1);
        $this->assertInstanceOf(Segment::class, $segment);

        $nutritionItem = new NutritionItem(
            'item-id',
            'external-ref',
            'Gel',
            new Carbs(25),
            new Quantity(2),
            null,
        );
        $segment->addNutritionItem($nutritionItem);

        $startCheckpointId = $segment->startCheckpoint->id;
        $endCheckpointId = $segment->endCheckpoint->id;

        // Add a custom checkpoint somewhere else (should not affect first segment)
        $customCheckpoint = new Checkpoint(
            id: 'custom-id',
            externalId: null,
            name: 'Custom',
            location: 'Location',
            distanceFromStart: 30000, // After aid station (25000) but before finish (50000)
            ascentFromStart: 1200,
            descentFromStart: 900,
            cutoff: null,
            assistanceAllowed: true,
            importedRace: $nutritionPlan->race,
            type: CheckpointType::Intermediate
        );
        $nutritionPlan->addCustomCheckpoint($customCheckpoint, ['seg-1', 'seg-2', 'seg-3']);

        // Find the segment with same checkpoints
        $segments = $nutritionPlan->getSegments()->filter(
            static fn (Segment $s) => $s->startCheckpoint->id === $startCheckpointId && $s->endCheckpoint->id === $endCheckpointId
        );

        $this->assertCount(1, $segments);
        $preservedSegment = $segments->first();
        $this->assertCount(1, $preservedSegment->getNutritionItems());
    }
}
