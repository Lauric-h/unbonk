<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class RunnerRace
{
    /**
     * @var Collection<int, Checkpoint>
     */
    private Collection $checkpoints;

    /**
     * @var Collection<int, Segment>
     */
    private Collection $segments;

    /**
     * @param Collection<int, Checkpoint>|null $checkpoints
     * @param Collection<int, Segment>|null    $segments
     */
    public function __construct(
        public string $id,
        public string $runnerId,
        public string $sourceRaceId,
        public string $eventId,
        public string $eventName,
        public string $name,
        public int $distance,
        public int $ascent,
        public int $descent,
        public \DateTimeImmutable $startDateTime,
        public string $location,
        ?Collection $checkpoints = null,
        ?Collection $segments = null,
    ) {
        $this->checkpoints = $checkpoints ?? new ArrayCollection();
        $this->segments = $segments ?? new ArrayCollection();

        $this->rebuildSegments();
    }

    /**
     * @return Checkpoint[]
     */
    public function orderedCheckpoints(): array
    {
        $checkpoints = $this->checkpoints->toArray();

        usort(
            $checkpoints,
            static fn (Checkpoint $a, Checkpoint $b) =>
                $a->distanceFromStart <=> $b->distanceFromStart
        );

        return $checkpoints;
    }

    /**
     * @return Collection<int, Segment>
     */
    public function segments(): Collection
    {
        return $this->segments;
    }

    public function checkpoint(string $checkpointId): ?Checkpoint
    {
        return $this->checkpoints->findFirst(
            static fn (int $key, Checkpoint $checkpoint) =>
                $checkpoint->id === $checkpointId
        );
    }

    public function segment(string $segmentId): ?Segment
    {
        return $this->segments->findFirst(
            static fn (int $key, Segment $segment) =>
                $segment->id === $segmentId
        );
    }

    public function addCheckpoint(Checkpoint $checkpoint): void
    {
        if ($this->checkpoints->contains($checkpoint)) {
            return;
        }

        $this->checkpoints->add($checkpoint);

        $this->rebuildSegments();
    }

    public function removeCheckpoint(string $checkpointId): void
    {
        $checkpoint = $this->checkpoint($checkpointId);

        if (null === $checkpoint) {
            throw new \DomainException(
                sprintf('Checkpoint "%s" not found.', $checkpointId)
            );
        }

        if (!$checkpoint->isCustom()) {
            throw new \DomainException(
                'Only custom checkpoints can be removed.'
            );
        }

        $this->checkpoints->removeElement($checkpoint);

        $this->rebuildSegments();
    }

    public function rebuildSegments(): void
    {
        $orderedCheckpoints = $this->orderedCheckpoints();

        if (count($orderedCheckpoints) < 2) {
            $this->segments->clear();

            return;
        }

        /** @var array<string, Segment> $existingSegments */
        $existingSegments = [];

        foreach ($this->segments as $segment) {
            $existingSegments[
            $this->segmentKey(
                $segment->fromCheckpoint->id,
                $segment->toCheckpoint->id,
            )
            ] = $segment;
        }

        $rebuiltSegments = new ArrayCollection();

        for ($i = 0; $i < count($orderedCheckpoints) - 1; ++$i) {
            $from = $orderedCheckpoints[$i];
            $to = $orderedCheckpoints[$i + 1];

            $key = $this->segmentKey(
                $from->id,
                $to->id,
            );

            if (isset($existingSegments[$key])) {
                $segment = $existingSegments[$key];

                $segment->refresh(
                    fromCheckpoint: $from,
                    toCheckpoint: $to,
                    position: $i + 1,
                );

                $rebuiltSegments->add($segment);

                continue;
            }

            $rebuiltSegments->add(
                Segment::create(
                    runnerRace: $this,
                    fromCheckpoint: $from,
                    toCheckpoint: $to,
                    position: $i + 1,
                )
            );
        }

        $this->segments = $rebuiltSegments;
    }

    private function segmentKey(
        string $fromCheckpointId,
        string $toCheckpointId,
    ): string {
        return sprintf('%s_%s', $fromCheckpointId, $toCheckpointId);
    }
}