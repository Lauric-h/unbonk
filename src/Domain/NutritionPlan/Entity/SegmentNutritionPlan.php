<?php

declare(strict_types=1);

namespace App\Domain\NutritionPlan\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class SegmentNutritionPlan
{
    /**
     * @var Collection<int, NutritionItem>
     */
    private Collection $nutritionItems;

    private NutritionPlan $nutritionPlan;

    public function __construct(
        private readonly string $id,
        private readonly string $segmentId,
        private readonly int $order,
        private ?Carbs $targetCarbs = null,
    ) {
        $this->nutritionItems = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSegmentId(): string
    {
        return $this->segmentId;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function getTargetCarbs(): ?Carbs
    {
        return $this->targetCarbs;
    }

    /** @internal Appelé uniquement par NutritionPlan::create(). */
    public function attachToPlan(NutritionPlan $nutritionPlan): void
    {
        $this->nutritionPlan = $nutritionPlan;
    }

    public function setTargetCarbs(?Carbs $targetCarbs): void
    {
        $this->targetCarbs = $targetCarbs;
    }

    /**
     * @return NutritionItem[]
     */
    public function getNutritionItems(): array
    {
        return $this->nutritionItems->toArray();
    }

    public function addNutritionItem(NutritionItem $nutritionItem): void
    {
        $nutritionItem->attachToSegmentPlan($this);
        $this->nutritionItems->add($nutritionItem);
    }

    public function removeNutritionItem(string $NutritionItemId): void
    {
        $NutritionItem = $this->nutritionItems->findFirst(
            static fn (int $key, NutritionItem $item): bool => $item->getId() === $NutritionItemId
        );

        if (null === $NutritionItem) {
            throw new \DomainException(sprintf('Food item "%s" not found.', $NutritionItemId));
        }

        $this->nutritionItems->removeElement($NutritionItem);
    }

    public function totalCarbs(): Carbs
    {
        return array_reduce(
            $this->nutritionItems->toArray(),
            static fn (Carbs $total, NutritionItem $item): Carbs => $total->add($item->totalCarbs()),
            Carbs::zero(),
        );
    }
}
