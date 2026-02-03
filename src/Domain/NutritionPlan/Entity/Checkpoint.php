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

    public function isCustom(): bool
    {
        return null === $this->externalId;
    }

    public function getCutoffInMinutes(): ?int
    {
        $cutoff = $this->getCutoff();
        if (null === $cutoff) {
            return null;
        }

        return $cutoff->getInMinutes($this->importedRace->startDateTime);
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
