<?php

namespace App\Application\Race\UseCase\GetMyRaces\ReadModel;

final readonly class RunnerRaceSummaryReadModel
{
    public function __construct(
        public string $id,
        public string $name,
        public string $eventName,
        public \DateTimeImmutable $startDateTime,
        public int $distance,
        public int $ascent,
        public int $descent,
    ) {
    }
}
