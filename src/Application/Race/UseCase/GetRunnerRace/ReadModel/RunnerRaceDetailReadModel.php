<?php

namespace App\Application\Race\UseCase\GetRunnerRace\ReadModel;

final readonly class RunnerRaceDetailReadModel
{
    /**
     * @param SegmentReadModel[] $segments
     */
    public function __construct(
        public string $id,
        public string $runnerId,
        public string $name,
        public string $eventName,
        public \DateTimeImmutable $startDateTime,
        public string $location,
        public int $distance,
        public int $ascent,
        public int $descent,
        public array $segments,
    ) {
    }
}
