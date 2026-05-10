<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\NutritionPlan\ValueObject\Cutoff;

/**
 * Base abstract class for all checkpoint types.
 * This is needed for Doctrine ORM's Single Table Inheritance.
 */
abstract class AbstractCheckpoint implements CheckpointInterface
{
    protected ?\DateTimeImmutable $cutoffTime;

    public function __construct(
        public string $id,
        public string $name,
        public string $location,
        public int $distanceFromStart,
        public int $ascentFromStart,
        public int $descentFromStart,
        ?Cutoff $cutoff,
        public bool $assistanceAllowed,
    ) {
        $this->cutoffTime = $cutoff?->dateTime;
        $this->validate();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getDistanceFromStart(): int
    {
        return $this->distanceFromStart;
    }

    public function getAscentFromStart(): int
    {
        return $this->ascentFromStart;
    }

    public function getDescentFromStart(): int
    {
        return $this->descentFromStart;
    }

    public function getCutoff(): ?Cutoff
    {
        if (null === $this->cutoffTime) {
            return null;
        }

        return new Cutoff($this->cutoffTime);
    }

    public function isAssistanceAllowed(): bool
    {
        return $this->assistanceAllowed;
    }

    abstract public function getType(): CheckpointType;

    abstract public function isEditable(): bool;

    protected function validate(): void
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
