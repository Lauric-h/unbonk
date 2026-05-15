<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

use App\Domain\Shared\Entity\Carbs;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class SegmentNutritionPlan
{
    /**
     * @param Collection<int, NutritionItem> $items
     */
    public function __construct(
        public string $id,
        public NutritionPlan $nutritionPlan,
        public Segment $segment,
        public ?Carbs $targetCarbs = null,
        private Collection $items = new ArrayCollection(),
    ) {
    }

    /**
     * @return Collection<int, NutritionItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(NutritionItem $item): void
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
        }
    }

    public function removeItem(string $itemId): void
    {
        $item = $this->getItemById($itemId);

        if (null === $item) {
            throw new \DomainException(\sprintf('Nutrition item %s not found', $itemId));
        }

        $this->items->removeElement($item);
    }

    public function getItemById(string $itemId): ?NutritionItem
    {
        return $this->items->findFirst(
            static fn (int $key, NutritionItem $item) => $item->id === $itemId
        );
    }
}
