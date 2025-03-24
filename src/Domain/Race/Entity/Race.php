<?php

namespace App\Domain\Race\Entity;

use App\Domain\Race\Exception\CheckpointWithSameDistanceException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Race
{
    /**
     * @param Collection<int, Checkpoint> $checkpoints
     */
    public function __construct(
        public string $id,
        public \DateTimeImmutable $date,
        public string $name,
        public Profile $profile,
        public Address $address,
        public string $runnerId,
        public Collection $checkpoints = new ArrayCollection(),
    ) {
    }

    public function update(
        string $name,
        \DateTimeImmutable $date,
        int $distance,
        int $elevationGain,
        int $elevationLoss,
        string $city,
        string $postalCode,
    ): void {
        $this->name = $name;
        $this->date = $date;

        $profile = new Profile($distance, $elevationGain, $elevationLoss);
        $this->profile = $profile;
        // To do in next PR
        //        $this->getFinishCheckpoint()->updateProfileMetrics($this->profile);

        $address = new Address($city, $postalCode);
        $this->address = $address;
    }

    public function addCheckpoint(Checkpoint $checkpoint): void
    {
        if ($this->getCheckpointAtDistance($checkpoint->metricsFromStart->distance)) {
            throw new CheckpointWithSameDistanceException($checkpoint->metricsFromStart->distance);
        }

        $this->checkpoints->add($checkpoint);
        $checkpoint->race = $this;
        $this->sortCheckpointByDistance();
    }

    public function getCheckpointAtDistance(int $distance): ?Checkpoint
    {
        $existingCheckpoints = $this->checkpoints->filter(static fn (Checkpoint $checkpoint) => $checkpoint->metricsFromStart->distance === $distance);

        if (\count($existingCheckpoints) > 1) {
            throw new \DomainException(\sprintf('Multiple checkpoint for same distance: %d', $distance));
        }

        return $existingCheckpoints->first() ?: null;
    }

    public function sortCheckpointByDistance(): void
    {
        $checkpoints = $this->checkpoints->toArray();
        usort($checkpoints, static fn (Checkpoint $a, Checkpoint $b) => $a->metricsFromStart->distance <=> $b->metricsFromStart->distance);

        $this->checkpoints->clear();
        foreach ($checkpoints as $checkpoint) {
            $this->checkpoints->add($checkpoint);
        }
    }

    public function getStartCheckpoint(): Checkpoint
    {
        $start = $this->checkpoints->first();
        if (false === $start) {
            throw new \DomainException('Race does not have start checkpoint');
        }

        if (CheckpointType::Start !== $start->checkpointType) {
            throw new \DomainException('Invalid Checkpoint type');
        }

        return $start;
    }

    public function getFinishCheckpoint(): Checkpoint
    {
        $finish = $this->checkpoints->last();
        if (false === $finish) {
            throw new \DomainException('Race does not have finish checkpoint');
        }

        if (CheckpointType::Finish !== $finish->checkpointType) {
            throw new \DomainException('Invalid Checkpoint type');
        }

        return $finish;
    }

    public function removeCheckpoint(Checkpoint $checkpoint): void
    {
        if ($checkpoint === $this->getStartCheckpoint()
            || $checkpoint === $this->getFinishCheckpoint()
        ) {
            throw new \DomainException('Cannot remove start or finish checkpoint');
        }

        $this->checkpoints->removeElement($checkpoint);
        $this->sortCheckpointByDistance();
    }
}
