<?php

namespace App\Domain\Race\Entity;

use App\Domain\Race\Exception\CheckpointWithSameDistanceException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Race
{
    private const DEFAULT_PACE = 10;

    /**
     * @var Collection<int, Checkpoint>
     */
    private Collection $checkpoints;

    private function __construct(
        public string $id,
        public \DateTimeImmutable $date,
        public string $name,
        public Profile $profile,
        public Address $address,
        public string $runnerId,
    ) {
        $this->checkpoints = new ArrayCollection();
    }

    public static function create(
        string $id,
        \DateTimeImmutable $date,
        string $name,
        Profile $profile,
        Address $address,
        string $runnerId,
        string $startCheckpointId,
        string $finishCheckpointId,
    ): self {
        $race = new self(
            $id,
            $date,
            $name,
            $profile,
            $address,
            $runnerId,
        );

        $startCheckpoint = new StartCheckpoint(
            $startCheckpointId,
            StartCheckpoint::DEFAULT_NAME,
            $address->city,
            $race
        );

        $finishCheckpoint = new FinishCheckpoint(
            $finishCheckpointId,
            FinishCheckpoint::DEFAULT_NAME,
            $address->city,
            $race->setDefaultEstimatedFinishDurationTime(),
            $race
        );

        $race->checkpoints->add($startCheckpoint);
        $race->checkpoints->add($finishCheckpoint);
        $race->sortCheckpointByDistance();

        return $race;
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

        $profile = Profile::create($distance, $elevationGain, $elevationLoss);
        $this->profile = $profile;
        $this->getFinishCheckpoint()->updateProfileMetrics($this->profile);

        $address = new Address($city, $postalCode);
        $this->address = $address;
    }

    public function addCheckpoint(AidStationCheckpoint|IntermediateCheckpoint $checkpoint): void
    {
        if ($this->getCheckpointAtDistance($checkpoint->getMetricsFromStart()->distance->value)) {
            throw new CheckpointWithSameDistanceException($checkpoint->getMetricsFromStart()->distance->value);
        }

        if ($checkpoint->getMetricsFromStart()->distance >= $this->profile->distance) {
            throw new \DomainException('New Checkpoint cannot exceed Race distance');
        }

        $this->checkpoints->add($checkpoint);
        $checkpoint->setRace($this);
        $this->sortCheckpointByDistance();
    }

    public function getCheckpointAtDistance(int $distance): ?Checkpoint
    {
        $existingCheckpoints = $this->checkpoints->filter(static fn (Checkpoint $checkpoint) => $checkpoint->getMetricsFromStart()->distance->value === $distance);

        if (\count($existingCheckpoints) > 1) {
            throw new \DomainException(\sprintf('Multiple checkpoint for same distance: %d', $distance));
        }

        return $existingCheckpoints->first() ?: null;
    }

    public function sortCheckpointByDistance(): void
    {
        $checkpoints = $this->checkpoints->toArray();
        usort($checkpoints, static fn (Checkpoint $a, Checkpoint $b) => $a->getMetricsFromStart()->distance->value <=> $b->getMetricsFromStart()->distance->value);

        $this->checkpoints->clear();
        foreach ($checkpoints as $checkpoint) {
            $this->checkpoints->add($checkpoint);
        }
    }

    public function getStartCheckpoint(): StartCheckpoint
    {
        $startCheckpoint = $this->checkpoints->filter(
            static fn (Checkpoint $checkpoint) => $checkpoint instanceof StartCheckpoint
        );

        if ($startCheckpoint->isEmpty()) {
            throw new \DomainException('Race must have at least one start checkpoint');
        }

        if (\count($startCheckpoint) > 1) {
            throw new \DomainException('Race must have only one start checkpoint');
        }

        $start = $startCheckpoint->first();
        assert($start instanceof StartCheckpoint);

        return $start;
    }

    public function getFinishCheckpoint(): FinishCheckpoint
    {
        $finishCheckpoint = $this->checkpoints->filter(
            static fn (Checkpoint $checkpoint) => $checkpoint instanceof FinishCheckpoint
        );

        if ($finishCheckpoint->isEmpty()) {
            throw new \DomainException('Race must have at least one finish checkpoint');
        }

        if (\count($finishCheckpoint) > 1) {
            throw new \DomainException('Race must have only one finish checkpoint');
        }

        $finish = $finishCheckpoint->first();
        assert($finish instanceof FinishCheckpoint);

        return $finish;
    }

    public function removeCheckpoint(Checkpoint $checkpoint): void
    {
        if ($checkpoint instanceof StartCheckpoint
            || $checkpoint instanceof FinishCheckpoint
        ) {
            throw new \DomainException('Cannot remove Start or Finish Checkpoint');
        }

        $this->checkpoints->removeElement($checkpoint);
        $this->sortCheckpointByDistance();
    }

    /**
     * @return Collection<int, Checkpoint>
     */
    public function getCheckpoints(): Collection
    {
        return $this->checkpoints;
    }

    private function setDefaultEstimatedFinishDurationTime(): int
    {
        $distance = $this->profile->distance->value;
        $ascentInKmEffort = $this->profile->ascent->value / 100 >= 1 ? $this->profile->ascent->value / 100 : 0;
        $defaultInHours = ($distance + $ascentInKmEffort) / self::DEFAULT_PACE;

        return (int) $defaultInHours * 60;
    }
}
