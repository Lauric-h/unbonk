<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

class Checkpoint
{
    public function __construct(
        public string $id,
        public RunnerRace $runnerRace,
        public ?string $externalCheckpointId, // null if custom checkpoint
        public string $name,
        public string $location,
        public int $distanceFromStart,
        public int $ascentFromStart,
        public int $descentFromStart,
        public ?Cutoff $cutoff,
        public bool $assistanceAllowed,
        public CheckpointType $type = CheckpointType::Intermediate,
    ) {
        $this->validate();
    }

    public function getCutoffInMinutes(): ?int
    {
        return $this->cutoff?->getInMinutes($this->runnerRace->startDateTime);
    }

    public function isAssistanceAllowed(): bool
    {
        return $this->assistanceAllowed;
    }

    public function getType(): CheckpointType
    {
        return $this->type;
    }

    /**
     * Check if this checkpoint is custom (created by user).
     * Custom checkpoints have no externalCheckpointId.
     */
    public function isCustom(): bool
    {
        return null === $this->externalCheckpointId;
    }

    /**
     * Check if this checkpoint is editable.
     * Only custom checkpoints can be edited.
     */
    public function isEditable(): bool
    {
        return $this->isCustom();
    }

    /**
     * Update checkpoint properties.
     * Only custom checkpoints can be updated.
     */
    public function update(
        string $name,
        string $location,
        int $distanceFromStart,
        int $ascentFromStart,
        int $descentFromStart,
        ?Cutoff $cutoff,
        bool $assistanceAllowed,
        CheckpointType $type,
    ): void {
        if (!$this->isEditable()) {
            throw new \DomainException('Cannot update imported checkpoints, only custom checkpoints can be updated');
        }

        $this->name = $name;
        $this->location = $location;
        $this->distanceFromStart = $distanceFromStart;
        $this->ascentFromStart = $ascentFromStart;
        $this->descentFromStart = $descentFromStart;
        $this->cutoff = $cutoff;
        $this->assistanceAllowed = $assistanceAllowed;
        $this->type = $type;

        $this->validate();
    }

    private function validate(): void
    {
        if ($this->distanceFromStart < 0) {
            throw new \DomainException(\sprintf('Distance from start cannot be negative: %d', $this->distanceFromStart));
        }

        if ($this->ascentFromStart < 0) {
            throw new \DomainException(\sprintf('Ascent from start cannot be negative: %d', $this->ascentFromStart));
        }

        if ($this->descentFromStart < 0) {
            throw new \DomainException(\sprintf('Descent from start cannot be negative: %d', $this->descentFromStart));
        }
    }
}
