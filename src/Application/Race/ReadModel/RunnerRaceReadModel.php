<?php

namespace App\Application\Race\ReadModel;

final readonly class RunnerRaceReadModel
{
    public function __construct(
        public string $id,
        public string $name,
        public string $eventName,
        public \DateTimeImmutable $startDateTime,
        public int $distance,
        public int $ascent,
        public int $descent,
    ) {}
}