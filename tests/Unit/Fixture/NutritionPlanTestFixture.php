<?php

namespace App\Tests\Unit\Fixture;

use App\Domain\NutritionPlan\Entity\Checkpoint;
use App\Domain\NutritionPlan\Entity\ImportedRace;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\ValueObject\Cutoff;

final class NutritionPlanTestFixture
{
    private string $id = 'nutrition-plan-id';
    private string $runnerId = 'runner-id';
    private ?ImportedRace $importedRace = null;
    private int $segmentIdCounter = 1;

    public function withId(string $id): self
    {
        $clone = clone $this;
        $clone->id = $id;

        return $clone;
    }

    public function withRunnerId(string $runnerId): self
    {
        $clone = clone $this;
        $clone->runnerId = $runnerId;

        return $clone;
    }

    public function withImportedRace(ImportedRace $importedRace): self
    {
        $clone = clone $this;
        $clone->importedRace = $importedRace;

        return $clone;
    }

    public function build(): NutritionPlan
    {
        $importedRace = $this->importedRace ?? self::createDefaultImportedRace();

        // Generate segment IDs based on checkpoint count
        $checkpointCount = \count($importedRace->getCheckpoints());
        $segmentIds = [];
        for ($i = 0; $i < $checkpointCount - 1; ++$i) {
            $segmentIds[] = 'segment-id-'.$this->segmentIdCounter++;
        }

        return NutritionPlan::createFromImportedRace(
            $this->id,
            $this->runnerId,
            $importedRace,
            $segmentIds,
        );
    }

    public static function createDefaultImportedRace(): ImportedRace
    {
        $importedRace = new ImportedRace(
            'imported-race-id',
            'external-race-id',
            'external-event-id',
            'Test Race',
            50000,
            2000,
            1500,
            new \DateTimeImmutable('2024-06-01 06:00:00'),
            'Mountain Town',
        );

        $startCheckpoint = new Checkpoint(
            'start-checkpoint-id',
            'start',
            'Start',
            'Mountain Town',
            0,
            0,
            0,
            null,
            false,
            $importedRace,
        );
        $importedRace->addCheckpoint($startCheckpoint);

        $aidStation = new Checkpoint(
            'aid-station-id',
            'aid-1',
            'Aid Station 1',
            'Valley',
            25000,
            1000,
            750,
            new Cutoff(new \DateTimeImmutable('2024-06-01 12:00:00')),
            true,
            $importedRace,
        );
        $importedRace->addCheckpoint($aidStation);

        $finishCheckpoint = new Checkpoint(
            'finish-checkpoint-id',
            'finish',
            'Finish',
            'Mountain Town',
            50000,
            2000,
            1500,
            null,
            false,
            $importedRace,
        );
        $importedRace->addCheckpoint($finishCheckpoint);

        return $importedRace;
    }

    public static function createImportedRaceWithCheckpoints(int $checkpointCount): ImportedRace
    {
        $distance = 100000;
        $importedRace = new ImportedRace(
            'imported-race-id',
            'external-race-id',
            'external-event-id',
            'Test Race',
            $distance,
            5000,
            4000,
            new \DateTimeImmutable('2024-06-01 06:00:00'),
            'Start City',
        );

        for ($i = 0; $i < $checkpointCount; ++$i) {
            $distanceFromStart = (int) (($distance / ($checkpointCount - 1)) * $i);
            $checkpoint = new Checkpoint(
                'checkpoint-'.$i,
                0 === $i ? 'start' : ($i === $checkpointCount - 1 ? 'finish' : 'cp-'.$i),
                0 === $i ? 'Start' : ($i === $checkpointCount - 1 ? 'Finish' : 'Checkpoint '.$i),
                'Location '.$i,
                $distanceFromStart,
                (int) ($distanceFromStart * 0.05),
                (int) ($distanceFromStart * 0.04),
                null,
                $i > 0 && $i < $checkpointCount - 1,
                $importedRace,
            );
            $importedRace->addCheckpoint($checkpoint);
        }

        return $importedRace;
    }
}
