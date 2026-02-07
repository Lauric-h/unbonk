<?php

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\NutritionPlan\ValueObject\Cutoff;

class Checkpoint
{
    private ?\DateTimeImmutable $cutoffTime;

    public function __construct(
        public string $id,
        public ?string $externalId,
        public string $name,
        public string $location,
        public int $distanceFromStart,
        public int $ascentFromStart,
        public int $descentFromStart,
        ?Cutoff $cutoff,
        public bool $assistanceAllowed,
        public ImportedRace $importedRace,
        public CheckpointType $type = CheckpointType::AidStation,
    ) {
        $this->cutoffTime = $cutoff?->dateTime;
        $this->validate();
    }

    public function getCutoff(): ?Cutoff
    {
        if (null === $this->cutoffTime) {
            return null;
        }

        return new Cutoff($this->cutoffTime);
    }

    public function isEditable(): bool
    {
        return $this->type->isEditable();
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
        if (!$this->isEditable()) {
            throw new \DomainException('Cannot update a non-editable checkpoint (AID_STATION type)');
        }

        $this->name = $name;
        $this->location = $location;
        $this->distanceFromStart = $distanceFromStart;
        $this->ascentFromStart = $ascentFromStart;
        $this->descentFromStart = $descentFromStart;
        $this->cutoffTime = $cutoff?->dateTime;
        $this->assistanceAllowed = $assistanceAllowed;

        $this->validate();
    }

    public function getCutoffInMinutes(): ?int
    {
        $cutoff = $this->getCutoff();

        return $cutoff?->getInMinutes($this->importedRace->startDateTime);
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
