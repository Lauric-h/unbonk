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
    ) {
    }

    public static function fromCheckpoint(Checkpoint $checkpoint): self
    {
        return new self(
            $checkpoint->id,
            $checkpoint->externalId,
            $checkpoint->name,
            $checkpoint->location,
            $checkpoint->distanceFromStart,
            $checkpoint->ascentFromStart,
            $checkpoint->descentFromStart,
            $checkpoint->getCutoffInMinutes(),
            $checkpoint->assistanceAllowed,
        );
    }
}
