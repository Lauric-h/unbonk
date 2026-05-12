<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\NutritionPlan;

use App\Domain\NutritionPlan\Entity\CustomCheckpoint;
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

        $customCheckpoint = new CustomCheckpoint(
            id: 'custom-checkpoint-id',
            name: 'Custom Point',
            location: 'Custom Location',
            distanceFromStart: 10000, // Somewhere between start (0) and aid station (25000)
            ascentFromStart: 500,
            descentFromStart: 400,
            cutoff: null,
            assistanceAllowed: true,
            nutritionPlan: $nutritionPlan,
        );

        // After adding checkpoint, we'll have 4 checkpoints = 3 segments
        $nutritionPlan->addCustomCheckpoint($customCheckpoint, ['seg-1', 'seg-2', 'seg-3']);

        $this->assertCount($initialSegmentCount + 1, $nutritionPlan->getSegments());
    }

    public function testAddCustomCheckpointThrowsExceptionForDuplicateDistance(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $customCheckpoint = new CustomCheckpoint(
            id: 'custom-checkpoint-id',
            name: 'Custom Point',
            location: 'Custom Location',
            distanceFromStart: 25000, // Same distance as existing aid station
            ascentFromStart: 1000,
            descentFromStart: 750,
            cutoff: null,
            assistanceAllowed: true,
            nutritionPlan: $nutritionPlan,
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('A checkpoint already exists at distance');

        $nutritionPlan->addCustomCheckpoint($customCheckpoint, ['seg-1', 'seg-2', 'seg-3']);
    }

    public function testRemoveCustomCheckpointRemovesSegment(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        // First add a custom checkpoint
        $customCheckpoint = new CustomCheckpoint(
            id: 'custom-checkpoint-id',
            name: 'Custom Point',
            location: 'Custom Location',
            distanceFromStart: 10000,
            ascentFromStart: 500,
            descentFromStart: 400,
            cutoff: null,
            assistanceAllowed: true,
            nutritionPlan: $nutritionPlan,
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
        $this->expectExceptionMessage('Cannot remove imported checkpoints');

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

        $startCheckpointId = $segment->startCheckpoint->getId();
        $endCheckpointId = $segment->endCheckpoint->getId();

        // Add a custom checkpoint somewhere else (should not affect first segment)
        $customCheckpoint = new CustomCheckpoint(
            id: 'custom-id',
            name: 'Custom',
            location: 'Location',
            distanceFromStart: 30000, // After aid station (25000) but before finish (50000)
            ascentFromStart: 1200,
            descentFromStart: 900,
            cutoff: null,
            assistanceAllowed: true,
            nutritionPlan: $nutritionPlan,
        );
        $nutritionPlan->addCustomCheckpoint($customCheckpoint, ['seg-1', 'seg-2', 'seg-3']);

        // Find the segment with same checkpoints
        $segments = $nutritionPlan->getSegments()->filter(
            static fn (Segment $s) => $s->startCheckpoint->getId() === $startCheckpointId && $s->endCheckpoint->getId() === $endCheckpointId
        );

        $this->assertCount(1, $segments);
        $preservedSegment = $segments->first();
        $this->assertCount(1, $preservedSegment->getNutritionItems());
    }

    public function testAddCustomCheckpointThrowsExceptionForDistanceAtStart(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $customCheckpoint = new CustomCheckpoint(
            id: 'custom-checkpoint-id',
            name: 'Invalid Start Point',
            location: 'Start',
            distanceFromStart: 0, // Cannot be at start (0)
            ascentFromStart: 0,
            descentFromStart: 0,
            cutoff: null,
            assistanceAllowed: true,
            nutritionPlan: $nutritionPlan,
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('must be greater than 0');

        $nutritionPlan->addCustomCheckpoint($customCheckpoint, ['seg-1', 'seg-2', 'seg-3']);
    }

    public function testAddCustomCheckpointThrowsExceptionForDistanceBeyondRace(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        $customCheckpoint = new CustomCheckpoint(
            id: 'custom-checkpoint-id',
            name: 'Beyond Finish',
            location: 'Too Far',
            distanceFromStart: 60000, // Event is 50000m
            ascentFromStart: 2500,
            descentFromStart: 2000,
            cutoff: null,
            assistanceAllowed: true,
            nutritionPlan: $nutritionPlan,
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('cannot be greater than or equal to race distance');

        $nutritionPlan->addCustomCheckpoint($customCheckpoint, ['seg-1', 'seg-2', 'seg-3']);
    }

    public function testAddCustomCheckpointThrowsExceptionForDecreasingAscent(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        // Imported race has: Start (0m, 0D+), Aid Station (25000m, 1000D+), Finish (50000m, 2000D+)
        $customCheckpoint = new CustomCheckpoint(
            id: 'custom-checkpoint-id',
            name: 'Invalid Ascent',
            location: 'Location',
            distanceFromStart: 30000, // After aid station (25000m)
            ascentFromStart: 800, // Less than aid station's 1000m - INVALID
            descentFromStart: 800,
            cutoff: null,
            assistanceAllowed: true,
            nutritionPlan: $nutritionPlan,
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('cumulative ascent');
        $this->expectExceptionMessage('cannot be less than previous checkpoint');

        $nutritionPlan->addCustomCheckpoint($customCheckpoint, ['seg-1', 'seg-2', 'seg-3']);
    }

    public function testAddCustomCheckpointThrowsExceptionForDecreasingDescent(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        // Imported race has: Start (0m, 0D-), Aid Station (25000m, 750D-), Finish (50000m, 1500D-)
        $customCheckpoint = new CustomCheckpoint(
            id: 'custom-checkpoint-id',
            name: 'Invalid Descent',
            location: 'Location',
            distanceFromStart: 30000, // After aid station (25000m)
            ascentFromStart: 1200, // OK: between 1000 and 2000
            descentFromStart: 500, // Less than aid station's 750m - INVALID
            cutoff: null,
            assistanceAllowed: true,
            nutritionPlan: $nutritionPlan,
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('cumulative descent');
        $this->expectExceptionMessage('cannot be less than previous checkpoint');

        $nutritionPlan->addCustomCheckpoint($customCheckpoint, ['seg-1', 'seg-2', 'seg-3']);
    }

    public function testAddCustomCheckpointThrowsExceptionForAscentExceedingNextCheckpoint(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        // Add checkpoint BEFORE aid station with ascent > aid station's ascent
        $customCheckpoint = new CustomCheckpoint(
            id: 'custom-checkpoint-id',
            name: 'Too Much Ascent',
            location: 'Location',
            distanceFromStart: 10000, // Before aid station (25000m)
            ascentFromStart: 1500, // More than aid station's 1000m - INVALID
            descentFromStart: 300,
            cutoff: null,
            assistanceAllowed: true,
            nutritionPlan: $nutritionPlan,
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('cumulative ascent');
        $this->expectExceptionMessage('cannot be greater than next checkpoint');

        $nutritionPlan->addCustomCheckpoint($customCheckpoint, ['seg-1', 'seg-2', 'seg-3']);
    }

    public function testUpdateCheckpointValidatesConsistency(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        // First add a valid custom checkpoint
        $customCheckpoint = new CustomCheckpoint(
            id: 'custom-checkpoint-id',
            name: 'Custom Point',
            location: 'Custom Location',
            distanceFromStart: 10000,
            ascentFromStart: 500,
            descentFromStart: 400,
            cutoff: null,
            assistanceAllowed: true,
            nutritionPlan: $nutritionPlan,
        );
        $nutritionPlan->addCustomCheckpoint($customCheckpoint, ['seg-1', 'seg-2', 'seg-3']);

        // Try to update with invalid ascent (less than start's 0, which is impossible, but let's try > next checkpoint)
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('cumulative ascent');

        $nutritionPlan->updateCheckpoint(
            checkpointId: 'custom-checkpoint-id',
            name: 'Updated Name',
            location: 'Updated Location',
            distanceFromStart: 10000,
            ascentFromStart: 1500, // More than aid station's 1000m - INVALID
            descentFromStart: 400,
            cutoff: null,
            assistanceAllowed: true,
            segmentIds: ['seg-1', 'seg-2', 'seg-3'],
        );
    }

    public function testAddCustomCheckpointSucceedsWithValidElevation(): void
    {
        $nutritionPlan = new NutritionPlanTestFixture()->build();

        // Add checkpoint with valid progressive elevation
        $customCheckpoint = new CustomCheckpoint(
            id: 'custom-checkpoint-id',
            name: 'Valid Point',
            location: 'Location',
            distanceFromStart: 30000, // Between aid station (25000) and finish (50000)
            ascentFromStart: 1500, // Between 1000 and 2000 - VALID
            descentFromStart: 1100, // Between 750 and 1500 - VALID
            cutoff: null,
            assistanceAllowed: true,
            nutritionPlan: $nutritionPlan,
        );

        $nutritionPlan->addCustomCheckpoint($customCheckpoint, ['seg-1', 'seg-2', 'seg-3']);

        $this->assertCount(3, $nutritionPlan->getSegments());
    }
}
