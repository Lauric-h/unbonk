<?php

declare(strict_types=1);

namespace App\Domain\Race\Entity;

class Checkpoint
{
    private RunnerRace $runnerRace;

    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $location,
        private readonly int $distanceFromStart,
        private readonly int $ascentFromStart,
        private readonly int $descentFromStart,
        private readonly Cutoff $cutoff,
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

    public function getCutoff(): Cutoff
    {
        return $this->cutoff;
    }

    public function isAssistanceAllowed(): bool
    {
        return $this->assistanceAllowed;
    }

    public function getType(): CheckpointType
    {
        return $this->type;
    }
}