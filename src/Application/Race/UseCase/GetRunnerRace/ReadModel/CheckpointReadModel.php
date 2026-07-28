<?php

namespace App\Application\Race\UseCase\GetRunnerRace\ReadModel;
final readonly class CheckpointReadModel
{
    public function __construct(
        public string $id,
        public string $name,
        public string $location,
        public int $distanceFromStart,
        public bool $assistanceAllowed,
        public ?\DateTimeImmutable $cutoffAt,
    ) {
    }
}
