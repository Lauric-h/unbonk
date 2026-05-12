<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * ImportedRace represents race data imported from an external source.
 * It contains only official/imported checkpoints and is quasi-immutable.
 * Custom checkpoints belong to individual NutritionPlans.
 */
class ImportedRace
{
    /** @var Collection<int, ImportedCheckpoint> */
    private Collection $checkpoints;

    /** @var Collection<int, NutritionPlan> */
    private Collection $nutritionPlans;

    /**
     * @param Collection<int, ImportedCheckpoint>|null $checkpoints
     */
    public function __construct(
        public string $id,
        public string $runnerId,
        public string $externalRaceId,
        public string $externalEventId,
        public string $eventName,
        public string $name,
        public int $distance,
        public int $ascent,
        public int $descent,
        public \DateTimeImmutable $startDateTime,
        public string $location,
        ?Collection $checkpoints = null,
    ) {
        $this->checkpoints = $checkpoints ?? new ArrayCollection();
        $this->nutritionPlans = new ArrayCollection();
    }

    /**
     * Add an imported checkpoint to the race.
     * This should only be used during race import/creation.
     */
    public function addCheckpoint(ImportedCheckpoint $checkpoint): void
    {
        if (!$this->checkpoints->contains($checkpoint)) {
            $this->checkpoints->add($checkpoint);
        }
    }

    /**
     * Get all imported checkpoints (official checkpoints from external source).
     *
     * @return Collection<int, ImportedCheckpoint>
     */
    public function getCheckpoints(): Collection
    {
        return $this->checkpoints;
    }

    /**
     * Get an imported checkpoint by its ID.
     */
    public function getCheckpointById(string $checkpointId): ?ImportedCheckpoint
    {
        return $this->checkpoints->findFirst(
            static fn (int $key, ImportedCheckpoint $checkpoint) => $checkpoint->id === $checkpointId
        );
    }

    /**
     * Get the start checkpoint (distance = 0).
     */
    public function getStartCheckpoint(): ?ImportedCheckpoint
    {
        return $this->checkpoints->findFirst(
            static fn (int $key, ImportedCheckpoint $checkpoint) => 0 === $checkpoint->distanceFromStart
        );
    }

    /**
     * Get the finish checkpoint (last one by distance).
     */
    public function getFinishCheckpoint(): ?ImportedCheckpoint
    {
        $checkpoints = $this->checkpoints->toArray();
        if (0 === \count($checkpoints)) {
            return null;
        }

        return $checkpoints[\count($checkpoints) - 1];
    }

    /**
     * @return Collection<int, NutritionPlan>
     */
    public function getNutritionPlans(): Collection
    {
        return $this->nutritionPlans;
    }

    public function addNutritionPlan(NutritionPlan $nutritionPlan): void
    {
        if (!$this->nutritionPlans->contains($nutritionPlan)) {
            $this->nutritionPlans->add($nutritionPlan);
        }
    }
}
