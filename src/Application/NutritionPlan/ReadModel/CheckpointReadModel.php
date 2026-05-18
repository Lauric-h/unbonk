<?php

namespace App\Application\NutritionPlan\ReadModel;


use App\Domain\NutritionPlan\Entity\Checkpoint;

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

    public static function fromCheckpoint(Checkpoint $checkpoint): self
    {
        return new self(
            id: $checkpoint->id,
            externalId: $checkpoint->externalCheckpointId,
            name: $checkpoint->name,
            location: $checkpoint->location,
            distanceFromStart: $checkpoint->distanceFromStart,
            ascentFromStart: $checkpoint->ascentFromStart,
            descentFromStart: $checkpoint->descentFromStart,
            cutoffInMinutes: $checkpoint->getCutoffInMinutes(),
            assistanceAllowed: $checkpoint->isAssistanceAllowed(),
            type: $checkpoint->getType()->value,
            isEditable: $checkpoint->isEditable(),
        );
    }
}
