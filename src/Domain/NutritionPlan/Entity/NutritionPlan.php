<?php

namespace App\Domain\NutritionPlan\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class NutritionPlan
{
    /** @var Collection<int, Segment> */
    private Collection $segments;

    /**
     * @param Collection<int, Segment>|null $segments
     */
    private function __construct(
        public string $id,
        public string $runnerId,
        public ImportedRace $importedRace,
        public \DateTimeImmutable $createdAt,
        ?Collection $segments = null,
    ) {
        $this->segments = $segments ?? new ArrayCollection();
    }

    /**
     * @param string[] $segmentIds IDs for the segments to be created (one per checkpoint pair)
     */
    public static function createFromImportedRace(
        string $id,
        string $runnerId,
        ImportedRace $importedRace,
        array $segmentIds,
    ): self {
        $nutritionPlan = new self(
            $id,
            $runnerId,
            $importedRace,
            new \DateTimeImmutable(),
        );

        $nutritionPlan->rebuildSegments($segmentIds);

        return $nutritionPlan;
    }

    /**
     * @param string[] $segmentIds IDs for the new segments to be created
     */
    public function addCustomCheckpoint(Checkpoint $checkpoint, array $segmentIds): void
    {
        if (!$checkpoint->isCustom()) {
            throw new \DomainException('Checkpoint must be custom (externalId must be null)');
        }

        $existingCheckpoint = $this->importedRace->getCheckpointAtDistance($checkpoint->distanceFromStart);
        if (null !== $existingCheckpoint) {
            throw new \DomainException(\sprintf('A checkpoint already exists at distance %d', $checkpoint->distanceFromStart));
        }

        $this->importedRace->addCheckpoint($checkpoint);
        $this->rebuildSegments($segmentIds);
    }

    /**
     * @param string[] $segmentIds IDs for the new segments to be created
     */
    public function removeCustomCheckpoint(string $checkpointId, array $segmentIds): void
    {
        $checkpoint = $this->importedRace->getCheckpointById($checkpointId);

        if (null === $checkpoint) {
            throw new \DomainException(\sprintf('Checkpoint with id %s not found', $checkpointId));
        }

        if (!$checkpoint->isCustom()) {
            throw new \DomainException('Cannot remove imported checkpoint, only custom checkpoints can be removed');
        }

        $this->importedRace->removeCheckpoint($checkpoint);
        $this->rebuildSegments($segmentIds);
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
     * @param string[] $segmentIds IDs for the new segments (must have at least checkpointCount - 1 elements)
     */
    public function rebuildSegments(array $segmentIds): void
    {
        $checkpoints = array_values($this->importedRace->getCheckpoints()->toArray());
        $checkpointCount = \count($checkpoints);

        if ($checkpointCount < 2) {
            $this->segments->clear();

            return;
        }

        $requiredSegmentCount = $checkpointCount - 1;
        if (\count($segmentIds) < $requiredSegmentCount) {
            throw new \DomainException(\sprintf(
                'Not enough segment IDs provided. Expected %d, got %d',
                $requiredSegmentCount,
                \count($segmentIds)
            ));
        }

        // Keep track of existing nutrition items by checkpoint pair
        $existingNutritionItems = [];
        foreach ($this->segments as $segment) {
            $key = \sprintf('%s-%s', $segment->startCheckpoint->id, $segment->endCheckpoint->id);
            $existingNutritionItems[$key] = $segment->getNutritionItems()->toArray();
        }

        $this->segments->clear();

        for ($i = 0; $i < $requiredSegmentCount; ++$i) {
            $startCheckpoint = $checkpoints[$i];
            $endCheckpoint = $checkpoints[$i + 1];

            $segment = Segment::createFromCheckpoints(
                $segmentIds[$i],
                $i + 1,
                $startCheckpoint,
                $endCheckpoint,
                $this,
            );

            // Restore nutrition items if this segment existed before
            $key = $startCheckpoint->id.'-'.$endCheckpoint->id;
            if (isset($existingNutritionItems[$key])) {
                foreach ($existingNutritionItems[$key] as $nutritionItem) {
                    $segment->addNutritionItem($nutritionItem);
                }
            }

            $this->segments->add($segment);
        }
    }
}
