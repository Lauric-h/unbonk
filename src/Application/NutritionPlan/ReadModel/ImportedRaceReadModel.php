<?php

namespace App\Application\NutritionPlan\ReadModel;

use App\Domain\NutritionPlan\Entity\Checkpoint;
use App\Domain\NutritionPlan\Entity\ImportedRace;

final readonly class ImportedRaceReadModel
{
    /**
     * @param CheckpointReadModel[] $checkpoints
     */
    public function __construct(
        public string $id,
        public string $externalRaceId,
        public string $externalEventId,
        public string $name,
        public int $distance,
        public int $ascent,
        public int $descent,
        public \DateTimeImmutable $startDateTime,
        public string $location,
        public array $checkpoints = [],
    ) {
    }

    public static function fromImportedRace(ImportedRace $importedRace): self
    {
        return new self(
            $importedRace->id,
            $importedRace->externalRaceId,
            $importedRace->externalEventId,
            $importedRace->name,
            $importedRace->distance,
            $importedRace->ascent,
            $importedRace->descent,
            $importedRace->startDateTime,
            $importedRace->location,
            array_map(
                static fn (Checkpoint $checkpoint) => CheckpointReadModel::fromCheckpoint($checkpoint),
                $importedRace->getCheckpoints()->toArray()
            ),
        );
    }
}
