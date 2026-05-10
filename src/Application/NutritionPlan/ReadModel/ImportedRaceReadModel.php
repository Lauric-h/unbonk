<?php

namespace App\Application\NutritionPlan\ReadModel;

use App\Domain\NutritionPlan\Entity\CheckpointInterface;
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
            id: $importedRace->id,
            externalRaceId: $importedRace->externalRaceId,
            externalEventId: $importedRace->externalEventId,
            name: $importedRace->name,
            distance: $importedRace->distance,
            ascent: $importedRace->ascent,
            descent: $importedRace->descent,
            startDateTime: $importedRace->startDateTime,
            location: $importedRace->location,
            checkpoints: array_map(
                static fn (CheckpointInterface $checkpoint) => CheckpointReadModel::fromCheckpoint($checkpoint),
                $importedRace->getCheckpoints()->toArray()
            ),
        );
    }
}
