<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;

class Segment
{
    public function __construct(
        public string $id,
        public RunnerRace $runnerRace,
        public Checkpoint $fromCheckpoint,
        public Checkpoint $toCheckpoint,
        public int $position,
    ) {
    }

    public static function create(
        string $id,
        RunnerRace $runnerRace,
        Checkpoint $fromCheckpoint,
        Checkpoint $toCheckpoint,
        int $position,
    ): self {
        return new self(
            id: $id,
            runnerRace: $runnerRace,
            fromCheckpoint: $fromCheckpoint,
            toCheckpoint: $toCheckpoint,
            position: $position,
        );
    }

    public function refresh(
        Checkpoint $fromCheckpoint,
        Checkpoint $toCheckpoint,
        int $position,
    ): void {
       $this->fromCheckpoint = $fromCheckpoint;
       $this->toCheckpoint = $toCheckpoint;
       $this->position = $position;
    }

    public function getDistance(): Distance
    {
        return new Distance($this->toCheckpoint->distanceFromStart - $this->fromCheckpoint->distanceFromStart);
    }

    public function getAscent(): Ascent
    {
        return new Ascent($this->toCheckpoint->ascentFromStart - $this->fromCheckpoint->ascentFromStart);
    }

    public function getDescent(): Descent
    {
        return new Descent($this->toCheckpoint->descentFromStart - $this->fromCheckpoint->descentFromStart);
    }
}
