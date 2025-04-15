<?php

namespace App\UI\Http\Rest\NutritionPlan\View;

use App\Domain\NutritionPlan\Entity\NutritionItem;

final class NutritionItemReadModel
{
    public function __construct(
        public string $id,
        public string $externalReference,
        public string $name,
        public int $carbs,
        public int $quantity,
        public ?int $calories = null,
    ) {
    }

    public static function fromNutritionItem(NutritionItem $nutritionItem): self
    {
        return new self(
            $nutritionItem->id,
            $nutritionItem->externalReference,
            $nutritionItem->name,
            $nutritionItem->carbs->value,
            $nutritionItem->quantity->value,
            $nutritionItem->calories?->value,
        );
    }
}
