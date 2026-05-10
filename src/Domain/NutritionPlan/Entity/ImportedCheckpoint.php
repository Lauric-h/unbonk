<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\NutritionPlan\ValueObject\Cutoff;

/**
 * Checkpoint imported from external race data.
 * These are immutable and belong to ImportedRace.
 */
class ImportedCheckpoint extends AbstractCheckpoint
{
    public function __construct(
        string $id,
        public readonly string $externalId,
        string $name,
        string $location,
        int $distanceFromStart,
        int $ascentFromStart,
        int $descentFromStart,
        ?Cutoff $cutoff,
        bool $assistanceAllowed,
        public readonly ImportedRace $importedRace,
        public readonly CheckpointType $type = CheckpointType::AidStation,
    ) {
        parent::__construct(
            id: $id,
            name: $name,
            location: $location,
            distanceFromStart: $distanceFromStart,
            ascentFromStart: $ascentFromStart,
            descentFromStart: $descentFromStart,
            cutoff: $cutoff,
            assistanceAllowed: $assistanceAllowed
        );
    }

    public function getType(): CheckpointType
    {
        return $this->type;
    }

    public function isEditable(): bool
    {
        return false; // Imported checkpoints are never editable
    }

    public function getCutoffInMinutes(): ?int
    {
        $cutoff = $this->getCutoff();

        return $cutoff?->getInMinutes($this->importedRace->startDateTime);
    }
}
