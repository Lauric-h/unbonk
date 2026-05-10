<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\NutritionPlan\ValueObject\Cutoff;

/**
 * Custom checkpoint created by the user for a specific NutritionPlan.
 * These are editable and belong to a NutritionPlan.
 */
class CustomCheckpoint extends AbstractCheckpoint
{
    public function __construct(
        string $id,
        string $name,
        string $location,
        int $distanceFromStart,
        int $ascentFromStart,
        int $descentFromStart,
        ?Cutoff $cutoff,
        bool $assistanceAllowed,
        public NutritionPlan $nutritionPlan,
    ) {
        parent::__construct(
            $id,
            $name,
            $location,
            $distanceFromStart,
            $ascentFromStart,
            $descentFromStart,
            $cutoff,
            $assistanceAllowed
        );
    }

    public function getType(): CheckpointType
    {
        return CheckpointType::Intermediate; // Custom checkpoints are always Intermediate
    }

    public function isEditable(): bool
    {
        return true; // Custom checkpoints are always editable
    }

    public function update(
        string $name,
        string $location,
        int $distanceFromStart,
        int $ascentFromStart,
        int $descentFromStart,
        ?Cutoff $cutoff,
        bool $assistanceAllowed,
    ): void {
        $this->name = $name;
        $this->location = $location;
        $this->distanceFromStart = $distanceFromStart;
        $this->ascentFromStart = $ascentFromStart;
        $this->descentFromStart = $descentFromStart;
        $this->cutoffTime = $cutoff?->dateTime;
        $this->assistanceAllowed = $assistanceAllowed;

        // Revalidate after update
        $this->validate();
    }

    public function getCutoffInMinutes(): ?int
    {
        $cutoff = $this->getCutoff();

        return $cutoff?->getInMinutes($this->nutritionPlan->race->startDateTime);
    }
}
