<?php

namespace App\Domain\NutritionPlan\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ImportedRace
{
    /** @var Collection<int, Checkpoint> */
    private Collection $checkpoints;

    /** @var Collection<int, NutritionPlan> */
    private Collection $nutritionPlans;

    /**
     * @param Collection<int, Checkpoint>|null $checkpoints
     */
    public function __construct(
        public string $id,
        public string $runnerId,
        public string $externalRaceId,
        public string $externalEventId,
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

    public function addCheckpoint(Checkpoint $checkpoint): void
    {
        if (!$this->checkpoints->contains($checkpoint)) {
            $this->checkpoints->add($checkpoint);
            $this->sortCheckpointsByDistance();
        }
    }

    public function removeCheckpoint(Checkpoint $checkpoint): void
    {
        if (!$checkpoint->isCustom()) {
            throw new \DomainException('Cannot remove imported checkpoint, only custom checkpoints can be removed');
        }

        $this->checkpoints->removeElement($checkpoint);
    }

    /**
     * @return Collection<int, Checkpoint>
     */
    public function getCheckpoints(): Collection
    {
        return $this->checkpoints;
    }

    public function getCheckpointById(string $checkpointId): ?Checkpoint
    {
        return $this->checkpoints->findFirst(
            static fn (int $key, Checkpoint $checkpoint) => $checkpoint->id === $checkpointId
        );
    }

    public function getCheckpointAtDistance(int $distance): ?Checkpoint
    {
        return $this->checkpoints->findFirst(
            static fn (int $key, Checkpoint $checkpoint) => $checkpoint->distanceFromStart === $distance
        );
    }

    public function getStartCheckpoint(): ?Checkpoint
    {
        return $this->checkpoints->findFirst(
            static fn (int $key, Checkpoint $checkpoint) => 0 === $checkpoint->distanceFromStart
        );
    }

    public function getFinishCheckpoint(): ?Checkpoint
    {
        $checkpoints = $this->checkpoints->toArray();
        if (0 === \count($checkpoints)) {
            return null;
        }

        return $checkpoints[\count($checkpoints) - 1];
    }

    private function sortCheckpointsByDistance(): void
    {
        $checkpoints = $this->checkpoints->toArray();
        usort($checkpoints, static fn (Checkpoint $a, Checkpoint $b) => $a->distanceFromStart <=> $b->distanceFromStart);

        $this->checkpoints->clear();
        foreach ($checkpoints as $checkpoint) {
            $this->checkpoints->add($checkpoint);
        }
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
