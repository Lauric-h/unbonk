<?php

declare(strict_types=1);

namespace App\Domain\Race\Entity;

class Segment
{
    private RunnerRace $runnerRace;

    public function __construct(
        private readonly string $id,
        private readonly Checkpoint $fromCheckpoint,
        private readonly Checkpoint $toCheckpoint,
        private readonly int $position,
    ) {
    }

    /** @internal */
    public function attachToRace(RunnerRace $runnerRace): void
    {
        $this->runnerRace = $runnerRace;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function fromCheckpoint(): Checkpoint
    {
        return $this->fromCheckpoint;
    }

    public function toCheckpoint(): Checkpoint
    {
        return $this->toCheckpoint;
    }

    public function position(): int
    {
        return $this->position;
    }

    public function distanceInMeters(): int
    {
        return $this->toCheckpoint->distanceFromStart() - $this->fromCheckpoint->distanceFromStart();
    }

    public function ascent(): int
    {
        return $this->toCheckpoint->ascentFromStart() - $this->fromCheckpoint->ascentFromStart();
    }

    public function descent(): int
    {
        return $this->toCheckpoint->descentFromStart() - $this->fromCheckpoint->descentFromStart();
    }

    public function cutoff(): ?Cutoff
    {
        return $this->toCheckpoint->cutoff();
    }
}