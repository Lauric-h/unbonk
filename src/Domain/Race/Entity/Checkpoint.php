<?php

declare(strict_types=1);

namespace App\Domain\Race\Entity;

final class Checkpoint
{
    public function __construct(
        private readonly string $id,
        private readonly RunnerRace $runnerRace,
        private readonly string $name,
        private readonly string $location,
        private readonly int $distanceFromStart,
        private readonly int $ascentFromStart,
        private readonly int $descentFromStart,
        private readonly ?Cutoff $cutoff,
        private readonly bool $assistanceAllowed,
        private readonly CheckpointType $type,
    ) {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function location(): string
    {
        return $this->location;
    }

    public function distanceFromStart(): int
    {
        return $this->distanceFromStart;
    }

    public function ascentFromStart(): int
    {
        return $this->ascentFromStart;
    }

    public function descentFromStart(): int
    {
        return $this->descentFromStart;
    }

    public function cutoff(): ?Cutoff
    {
        return $this->cutoff;
    }

    public function assistanceAllowed(): bool
    {
        return $this->assistanceAllowed;
    }

    public function type(): CheckpointType
    {
        return $this->type;
    }
}