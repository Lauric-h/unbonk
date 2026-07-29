<?php

declare(strict_types=1);

namespace App\Domain\Race\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class RunnerRace
{
    /**
     * @var Collection<int, Checkpoint>
     */
    private Collection $checkpoints;

    /**
     * @var Collection<int, Segment>
     */
    private Collection $segments;

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
    ) {
        $this->checkpoints = new ArrayCollection();
        $this->segments = new ArrayCollection();
    }

    /**
     * @param Checkpoint[]           $checkpoints     Doivent être fournis triés ou non — l'ordre
     *                                                 est recalculé en interne sur distanceFromStart
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
        if (count($checkpoints) < 2) {
            throw new \DomainException('A race must have at least a start and finish checkpoint.');
        }

        $runnerRace = new self(
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
        );

        $orderedCheckpoints = self::orderByDistance($checkpoints);

        foreach ($orderedCheckpoints as $checkpoint) {
            $checkpoint->attachToRace($runnerRace);
            $runnerRace->checkpoints->add($checkpoint);
        }

        foreach (self::buildSegments($orderedCheckpoints, $segmentIdGenerator) as $segment) {
            $segment->attachToRace($runnerRace);
            $runnerRace->segments->add($segment);
        }

        return $runnerRace;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRunnerId(): string
    {
        return $this->runnerId;
    }

    public function getSourceRaceId(): string
    {
        return $this->sourceRaceId;
    }

    public function getEventId(): string
    {
        return $this->eventId;
    }

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDistance(): int
    {
        return $this->distance;
    }

    public function getAscent(): int
    {
        return $this->ascent;
    }

    public function getDescent(): int
    {
        return $this->descent;
    }

    public function getStartDateTime(): \DateTimeImmutable
    {
        return $this->startDateTime;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return Checkpoint[]
     */
    public function checkpoints(): array
    {
        return $this->checkpoints->toArray();
    }

    /**
     * @return Segment[]
     */
    public function segments(): array
    {
        return $this->segments->toArray();
    }

    public function checkpoint(string $checkpointId): Checkpoint
    {
        $checkpoint = $this->checkpoints->findFirst(
            static fn (int $key, Checkpoint $checkpoint): bool => $checkpoint->getId() === $checkpointId
        );

        if (null === $checkpoint) {
            throw new \DomainException(sprintf('Checkpoint "%s" not found.', $checkpointId));
        }

        return $checkpoint;
    }

    public function segment(string $segmentId): Segment
    {
        $segment = $this->segments->findFirst(
            static fn (int $key, Segment $segment): bool => $segment->id() === $segmentId
        );

        if (null === $segment) {
            throw new \DomainException(sprintf('Segment "%s" not found.', $segmentId));
        }

        return $segment;
    }

    /**
     * @param Checkpoint[] $checkpoints
     * @return Checkpoint[]
     */
    private static function orderByDistance(array $checkpoints): array
    {
        usort(
            $checkpoints,
            static fn (Checkpoint $a, Checkpoint $b): int => $a->getDistanceFromStart() <=> $b->getDescentFromStart()
        );

        return array_values($checkpoints);
    }

    /**
     * @param Checkpoint[]           $orderedCheckpoints
     * @param callable(int): string $segmentIdGenerator
     * @return Segment[]
     */
    private static function buildSegments(array $orderedCheckpoints, callable $segmentIdGenerator): array
    {
        $segments = [];

        for ($i = 0; $i < count($orderedCheckpoints) - 1; ++$i) {
            $segments[] = new Segment(
                id: $segmentIdGenerator($i + 1),
                fromCheckpoint: $orderedCheckpoints[$i],
                toCheckpoint: $orderedCheckpoints[$i + 1],
                position: $i + 1,
            );
        }

        return $segments;
    }
}