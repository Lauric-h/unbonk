<?php

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Segment
{
    /** @var Collection<int, NutritionItem> */
    private Collection $nutritionItems;

    /**
     * @param Collection<int, NutritionItem>|null $nutritionItems
     */
    public function __construct(
        public string $id,
        public int $position,
        public CheckpointInterface $startCheckpoint,
        public CheckpointInterface $endCheckpoint,
        public NutritionPlan $nutritionPlan,
        ?Collection $nutritionItems = null,
    ) {
        $this->nutritionItems = $nutritionItems ?? new ArrayCollection();
    }

    public static function createFromCheckpoints(
        string $id,
        int $position,
        CheckpointInterface $startCheckpoint,
        CheckpointInterface $endCheckpoint,
        NutritionPlan $nutritionPlan,
    ): self {
        return new self(
            $id,
            $position,
            $startCheckpoint,
            $endCheckpoint,
            $nutritionPlan,
        );
    }

    public function getDistance(): int
    {
        return new Distance($this->endCheckpoint->getDistanceFromStart() - $this->startCheckpoint->getDistanceFromStart());
    }

    public function getAscent(): Ascent
    {
        return new Ascent($this->endCheckpoint->getAscentFromStart() - $this->startCheckpoint->getAscentFromStart());
    }

    public function getDescent(): Descent
    {
        return new Descent($this->endCheckpoint->getDescentFromStart() - $this->startCheckpoint->getDescentFromStart());
    }

    /**
     * @return Collection<int, NutritionItem>
     */
    public function getNutritionItems(): Collection
    {
        return $this->nutritionItems;
    }

    public function addNutritionItem(NutritionItem $nutritionItem): void
    {
        $existingNutritionItem = $this->getNutritionItemByExternalReference($nutritionItem->externalReference);
        if (null !== $existingNutritionItem) {
            $this->nutritionItems->removeElement($existingNutritionItem);
        }

        $this->nutritionItems->add($nutritionItem);
        $nutritionItem->segment = $this;
    }

    public function getNutritionItemByExternalReference(string $externalReference): ?NutritionItem
    {
        return $this->nutritionItems->findFirst(
            static fn (int $key, NutritionItem $nutritionItem) => $nutritionItem->externalReference === $externalReference
        );
    }

    public function getNutritionItemById(string $nutritionItemId): ?NutritionItem
    {
        return $this->nutritionItems->findFirst(
            static fn (int $key, NutritionItem $nutritionItem) => $nutritionItem->id === $nutritionItemId
        );
    }

    public function removeNutritionItem(string $nutritionItemId): void
    {
        $nutritionItem = $this->getNutritionItemById($nutritionItemId);
        if (null === $nutritionItem) {
            throw new \DomainException(\sprintf('Segment does not have NutritionItem with id %s', $nutritionItemId));
        }
        $this->nutritionItems->removeElement($nutritionItem);
    }
}
