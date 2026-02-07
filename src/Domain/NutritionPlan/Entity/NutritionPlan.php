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

    /**
     * @param Collection<int, Segment>|null $segments
     */
    private function __construct(
        public string $id,
        public ImportedRace $race,
        public ?string $name,
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
     * @param string[] $segmentIds IDs for the new segments to be created
     */
    public function addCustomCheckpoint(Checkpoint $checkpoint, array $segmentIds): void
    {
        if (!$checkpoint->isEditable()) {
            throw new \DomainException('Checkpoint must be custom (externalId must be null)');
        }

        $existingCheckpoint = $this->race->getCheckpointAtDistance($checkpoint->distanceFromStart);
        if (null !== $existingCheckpoint) {
            throw new \DomainException(\sprintf('A checkpoint already exists at distance %d', $checkpoint->distanceFromStart));
        }

        $this->race->addCheckpoint($checkpoint);
        $this->rebuildSegments($segmentIds);
    }

    /**
     * @param string[] $segmentIds IDs for the new segments to be created
     */
    public function removeCheckpoint(string $checkpointId, array $segmentIds): void
    {
        $checkpoint = $this->race->getCheckpointById($checkpointId);

        if (null === $checkpoint) {
            throw new \DomainException(\sprintf('Checkpoint with id %s not found', $checkpointId));
        }

        if (!$checkpoint->isEditable()) {
            throw new \DomainException('Cannot remove a non-editable checkpoint (AID_STATION type)');
        }

        $this->race->removeCheckpoint($checkpoint);
        $this->rebuildSegments($segmentIds);
    }

    /**
     * Updates an existing checkpoint (only INTERMEDIATE type checkpoints can be updated).
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
        $checkpoint = $this->race->getCheckpointById($checkpointId);

        if (null === $checkpoint) {
            throw new \DomainException(\sprintf('Checkpoint with id %s not found', $checkpointId));
        }

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

        // If distance changed, we need to resort checkpoints and rebuild segments
        if ($oldDistance !== $distanceFromStart) {
            $this->race->sortCheckpointsByDistance();
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
     * @param string[] $segmentIds IDs for the new segments (must have at least checkpointCount - 1 elements)
     */
    public function rebuildSegments(array $segmentIds): void
    {
        $checkpoints = array_values($this->race->getCheckpoints()->toArray());
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
