<?php

declare(strict_types=1);

namespace App\Domain\Race\Entity;

final class RunnerRace
{
    /**
     * @var Checkpoint[]
     */
    private array $checkpoints;

    /**
     * @var Segment[]
     */
    private array $segments;

    /**
     * @param Checkpoint[] $checkpoints Doivent être fournis triés ou non — l'ordre est
     *                                  recalculé en interne sur distanceFromStart
     */
    private function __construct(
        private readonly string $id,
        private readonly string $runnerId,
        private readonly string $sourceRaceId,
        private readonly string $eventId,
        private readonly string $eventName,
        private readonly string $name,
        private readonly int $distance,
        private readonly int $ascent,
        private readonly int $descent,
        private readonly \DateTimeImmutable $startDateTime,
        private readonly string $location,
        array $checkpoints,
        private readonly \Closure $segmentIdGenerator,
    ) {
        if (count($checkpoints) < 2) {
            throw new \DomainException('A race must have at least a start and finish checkpoint.');
        }

        $this->checkpoints = $this->orderByDistance($checkpoints);
        $this->segments = $this->buildSegments();
    }

    /**
     * @param Checkpoint[] $checkpoints
     * @param callable(int): string $segmentIdGenerator Fournit un ID par segment (position)
     */
    public static function import(
        string $id,
        string $runnerId,
        string $sourceRaceId,
        string $eventId,
        string $eventName,
        string $name,
        int $distance,
        int $ascent,
        int $descent,
        \DateTimeImmutable $startDateTime,
        string $location,
        array $checkpoints,
        callable $segmentIdGenerator,
    ): self {
        return new self(
            id: $id,
            runnerId: $runnerId,
            sourceRaceId: $sourceRaceId,
            eventId: $eventId,
            eventName: $eventName,
            name: $name,
            distance: $distance,
            ascent: $ascent,
            descent: $descent,
            startDateTime: $startDateTime,
            location: $location,
            checkpoints: $checkpoints,
            segmentIdGenerator: \Closure::fromCallable($segmentIdGenerator),
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function runnerId(): string
    {
        return $this->runnerId;
    }

    public function sourceRaceId(): string
    {
        return $this->sourceRaceId;
    }

    public function eventId(): string
    {
        return $this->eventId;
    }

    public function eventName(): string
    {
        return $this->eventName;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function distance(): int
    {
        return $this->distance;
    }

    public function ascent(): int
    {
        return $this->ascent;
    }

    public function descent(): int
    {
        return $this->descent;
    }

    public function startDateTime(): \DateTimeImmutable
    {
        return $this->startDateTime;
    }

    public function location(): string
    {
        return $this->location;
    }

    /**
     * @return Checkpoint[]
     */
    public function checkpoints(): array
    {
        return $this->checkpoints;
    }

    /**
     * @return Segment[]
     */
    public function segments(): array
    {
        return $this->segments;
    }

    public function checkpoint(string $checkpointId): Checkpoint
    {
        foreach ($this->checkpoints as $checkpoint) {
            if ($checkpoint->id() === $checkpointId) {
                return $checkpoint;
            }
        }

        throw new \DomainException(sprintf('Checkpoint "%s" not found.', $checkpointId));
    }

    public function segment(string $segmentId): Segment
    {
        foreach ($this->segments as $segment) {
            if ($segment->id() === $segmentId) {
                return $segment;
            }
        }

        throw new \DomainException(sprintf('Segment "%s" not found.', $segmentId));
    }

    /**
     * @param Checkpoint[] $checkpoints
     * @return Checkpoint[]
     */
    private function orderByDistance(array $checkpoints): array
    {
        usort($checkpoints, static fn (Checkpoint $a, Checkpoint $b) => $a->distanceFromStart() <=> $b->distanceFromStart());

        return array_values($checkpoints);
    }

    /**
     * @return Segment[]
     */
    private function buildSegments(): array
    {
        $segments = [];

        for ($i = 0; $i < count($this->checkpoints) - 1; ++$i) {
            $segments[] = new Segment(
                id: ($this->segmentIdGenerator)($i + 1),
                fromCheckpoint: $this->checkpoints[$i],
                toCheckpoint: $this->checkpoints[$i + 1],
                position: $i + 1,
            );
        }

        return $segments;
    }
}