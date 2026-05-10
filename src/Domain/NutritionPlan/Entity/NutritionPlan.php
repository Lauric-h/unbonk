<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\NutritionPlan\ValueObject\Cutoff;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class NutritionPlan
{
    /** @var Collection<int, Segment> */
    private Collection $segments;

    /** @var Collection<int, CustomCheckpoint> */
    private Collection $customCheckpoints;

    /**
     * @param Collection<int, Segment>|null $segments
     * @param Collection<int, CustomCheckpoint>|null $customCheckpoints
     */
    private function __construct(
        public string $id,
        public ImportedRace $race,
        public ?string $name,
        public \DateTimeImmutable $createdAt,
        ?Collection $segments = null,
        ?Collection $customCheckpoints = null,
    ) {
        $this->segments = $segments ?? new ArrayCollection();
        $this->customCheckpoints = $customCheckpoints ?? new ArrayCollection();
    }

    /**
     * @param string[] $segmentIds IDs for the segments to be created (one per checkpoint pair)
     */
    public static function createFromImportedRace(
        string $id,
        ImportedRace $race,
        array $segmentIds,
        ?string $name = null,
    ): self {
        $nutritionPlan = new self(
            id: $id,
            race: $race,
            name: $name,
            createdAt: new \DateTimeImmutable(),
        );

        $nutritionPlan->rebuildSegments($segmentIds);

        $race->addNutritionPlan($nutritionPlan);

        return $nutritionPlan;
    }

    /**
     * Get all checkpoints (imported + custom) sorted by distance.
     *
     * @return CheckpointInterface[]
     */
    public function getAllCheckpoints(): array
    {
        $importedCheckpoints = $this->race->getCheckpoints()->toArray();
        $customCheckpoints = $this->customCheckpoints->toArray();

        $allCheckpoints = array_merge($importedCheckpoints, $customCheckpoints);

        usort($allCheckpoints, static fn (CheckpointInterface $a, CheckpointInterface $b) => $a->getDistanceFromStart() <=> $b->getDistanceFromStart());

        return $allCheckpoints;
    }

    /**
     * Get a checkpoint by ID (searches both imported and custom checkpoints).
     */
    public function getCheckpointById(string $checkpointId): ?CheckpointInterface
    {
        // Search in imported checkpoints first
        $importedCheckpoint = $this->race->getCheckpointById($checkpointId);
        if (null !== $importedCheckpoint) {
            return $importedCheckpoint;
        }

        // Then search in custom checkpoints
        return $this->customCheckpoints->findFirst(
            static fn (int $key, CustomCheckpoint $checkpoint) => $checkpoint->id === $checkpointId
        );
    }

    /**
     * Check if a checkpoint exists at a given distance.
     */
    private function hasCheckpointAtDistance(int $distance): bool
    {
        foreach ($this->getAllCheckpoints() as $checkpoint) {
            if ($checkpoint->getDistanceFromStart() === $distance) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add a custom checkpoint to this nutrition plan.
     *
     * @param string[] $segmentIds IDs for the new segments to be created
     */
    public function addCustomCheckpoint(CustomCheckpoint $checkpoint, array $segmentIds): void
    {
        if ($this->hasCheckpointAtDistance($checkpoint->distanceFromStart)) {
            throw new \DomainException(\sprintf('A checkpoint already exists at distance %d', $checkpoint->distanceFromStart));
        }

        $this->customCheckpoints->add($checkpoint);
        $this->rebuildSegments($segmentIds);
    }

    /**
     * Remove a custom checkpoint from this nutrition plan.
     *
     * @param string[] $segmentIds IDs for the new segments to be created
     */
    public function removeCheckpoint(string $checkpointId, array $segmentIds): void
    {
        $checkpoint = $this->getCheckpointById($checkpointId);

        if (null === $checkpoint) {
            throw new \DomainException(\sprintf('Checkpoint with id %s not found', $checkpointId));
        }

        if (!$checkpoint->isEditable()) {
            throw new \DomainException('Cannot remove imported checkpoints, only custom checkpoints can be removed');
        }

        // It's a custom checkpoint, remove it
        $this->customCheckpoints->removeElement($checkpoint);
        $this->rebuildSegments($segmentIds);
    }

    /**
     * Update an existing custom checkpoint.
     * Only custom checkpoints can be updated (imported checkpoints are immutable).
     *
     * @param string[] $segmentIds IDs for the new segments to be created (needed if distance changes)
     */
    public function updateCheckpoint(
        string $checkpointId,
        string $name,
        string $location,
        int $distanceFromStart,
        int $ascentFromStart,
        int $descentFromStart,
        ?Cutoff $cutoff,
        bool $assistanceAllowed,
        array $segmentIds,
    ): void {
        $checkpoint = $this->getCheckpointById($checkpointId);

        if (null === $checkpoint) {
            throw new \DomainException(\sprintf('Checkpoint with id %s not found', $checkpointId));
        }

        if (!$checkpoint->isEditable()) {
            throw new \DomainException('Cannot update imported checkpoints, only custom checkpoints can be updated');
        }

        // It's a CustomCheckpoint, we can safely cast
        /** @var CustomCheckpoint $checkpoint */
        $oldDistance = $checkpoint->distanceFromStart;

        $checkpoint->update(
            $name,
            $location,
            $distanceFromStart,
            $ascentFromStart,
            $descentFromStart,
            $cutoff,
            $assistanceAllowed,
        );

        // If distance changed, we need to rebuild segments
        if ($oldDistance !== $distanceFromStart) {
            $this->rebuildSegments($segmentIds);
        }
    }

    /**
     * @return Collection<int, Segment>
     */
    public function getSegments(): Collection
    {
        return $this->segments;
    }

    public function getSegmentById(string $segmentId): ?Segment
    {
        return $this->segments->findFirst(
            static fn (int $key, Segment $segment) => $segment->id === $segmentId
        );
    }

    public function getSegmentByPosition(int $position): ?Segment
    {
        return $this->segments->findFirst(
            static fn (int $key, Segment $segment) => $segment->position === $position
        );
    }

    /**
     * Rebuild all segments based on current checkpoints (imported + custom).
     *
     * @param string[] $segmentIds IDs for the new segments (must have at least checkpointCount - 1 elements)
     */
    public function rebuildSegments(array $segmentIds): void
    {
        $checkpoints = $this->getAllCheckpoints();
        $checkpointCount = \count($checkpoints);

        if ($checkpointCount < 2) {
            $this->segments->clear();

            return;
        }

        $requiredSegmentCount = $checkpointCount - 1;
        if (\count($segmentIds) < $requiredSegmentCount) {
            throw new \DomainException(\sprintf('Not enough segment IDs provided. Expected %d, got %d', $requiredSegmentCount, \count($segmentIds)));
        }

        // Keep track of existing nutrition items by START checkpoint only
        // This ensures items are redistributed to the segment starting from the same checkpoint
        // Example: CP1 -> SEG1(items) -> CP2 -> SEG2 -> CP3
        // If we add CP_NEW between CP1 and CP2: CP1 -> SEG1(items) -> CP_NEW -> SEG_NEW -> CP2 -> SEG2 -> CP3
        // Items stay on SEG1 because it still starts from CP1
        $nutritionItemsByStartCheckpoint = [];
        foreach ($this->segments as $segment) {
            $startCheckpointId = $segment->startCheckpoint->id;
            if (!isset($nutritionItemsByStartCheckpoint[$startCheckpointId])) {
                $nutritionItemsByStartCheckpoint[$startCheckpointId] = [];
            }
            foreach ($segment->getNutritionItems()->toArray() as $nutritionItem) {
                $nutritionItemsByStartCheckpoint[$startCheckpointId][] = $nutritionItem;
            }
        }

        $this->segments->clear();

        for ($i = 0; $i < $requiredSegmentCount; ++$i) {
            $startCheckpoint = $checkpoints[$i];
            $endCheckpoint = $checkpoints[$i + 1];

            $segment = Segment::createFromCheckpoints(
                id: $segmentIds[$i],
                position: $i + 1,
                startCheckpoint: $startCheckpoint,
                endCheckpoint: $endCheckpoint,
                nutritionPlan: $this,
            );

            // Restore nutrition items for this start checkpoint
            if (isset($nutritionItemsByStartCheckpoint[$startCheckpoint->id])) {
                foreach ($nutritionItemsByStartCheckpoint[$startCheckpoint->id] as $nutritionItem) {
                    $segment->addNutritionItem($nutritionItem);
                }
            }

            $this->segments->add($segment);
        }
    }
}
