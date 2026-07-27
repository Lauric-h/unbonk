<?php

declare(strict_types=1);

namespace App\Domain\Race\Entity;

final class Checkpoint
{
    private RunnerRace $runnerRace;

    public function __construct(
        private readonly string $id,
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

    /**
     * @internal Appelé uniquement par RunnerRace::import(). Ne pas utiliser ailleurs :
     *           ce n'est pas une opération métier, juste la liaison bidirectionnelle
     *           nécessaire à Doctrine.
     */
    public function attachToRace(RunnerRace $runnerRace): void
    {
        $this->runnerRace = $runnerRace;
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