<?php

namespace App\Domain\NutritionPlan\Entity;

final class NutritionItem
{
    private SegmentNutritionPlan $segmentNutritionPlan;

    public function __construct(
        private readonly string $id,
        private readonly string $foodCatalogId,
        private readonly string $name,
        private readonly Carbs  $carbsPerUnit,
        private int             $quantity,
    )
    {
        if ($this->quantity < 1) {
            throw new \DomainException('Quantity must be at least 1.');
        }
    }

    /** @internal Appelé uniquement par SegmentNutritionPlan::addFoodItem(). */
    public function attachToSegmentPlan(SegmentNutritionPlan $segmentNutritionPlan): void
    {
        $this->segmentNutritionPlan = $segmentNutritionPlan;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFoodCatalogId(): string
    {
        return $this->foodCatalogId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCarbsPerUnit(): Carbs
    {
        return $this->carbsPerUnit;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function changeQuantity(int $quantity): void
    {
        if ($quantity < 1) {
            throw new \DomainException('Quantity must be at least 1.');
        }

        $this->quantity = $quantity;
    }

    public function totalCarbs(): Carbs
    {
        return $this->carbsPerUnit->multiply($this->quantity);
    }
}
