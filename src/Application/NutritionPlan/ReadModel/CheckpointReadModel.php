<?php

namespace App\Application\NutritionPlan\ReadModel;

use App\Domain\NutritionPlan\Entity\CheckpointInterface;

final readonly class CheckpointReadModel
{
    public function __construct(
        public string $id,
        public ?string $externalId,
        public string $name,
        public string $location,
        public int $distanceFromStart,
        public int $ascentFromStart,
        public int $descentFromStart,
        public ?int $cutoffInMinutes,
        public bool $assistanceAllowed,
        public string $type,
        public bool $isEditable,
    ) {
    }

    public static function fromCheckpoint(CheckpointInterface $checkpoint): self
    {
        // For imported checkpoints, externalId exists
        // For custom checkpoints, it doesn't (they don't have an externalId property)
        $externalId = null;
        if (method_exists($checkpoint, 'externalId')) {
            $externalId = $checkpoint->getId();
        }

        // Get cutoff in minutes based on checkpoint type
        $cutoffInMinutes = null;
        if (method_exists($checkpoint, 'getCutoffInMinutes')) {
            $cutoffInMinutes = $checkpoint->getCutoffInMinutes();
        }

        return new self(
            id: $checkpoint->getId(),
            externalId: $externalId,
            name: $checkpoint->getName(),
            location: $checkpoint->getLocation(),
            distanceFromStart: $checkpoint->getDistanceFromStart(),
            ascentFromStart: $checkpoint->getAscentFromStart(),
            descentFromStart: $checkpoint->getDescentFromStart(),
            cutoffInMinutes: $cutoffInMinutes,
            assistanceAllowed: $checkpoint->isAssistanceAllowed(),
            type: $checkpoint->getType()->value,
            isEditable: $checkpoint->isEditable(),
        );
    }
}
