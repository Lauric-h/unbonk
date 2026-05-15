<?php

namespace App\Application\NutritionPlan\ReadModel;

use App\Domain\NutritionPlan\Entity\NutritionItem;

final class NutritionItemReadModel
{
    public function __construct(
        public string $id,
        public string $foodItemId,
        public int $quantity,
        public int $carbs,
    ) {
    }

    public static function fromNutritionItem(NutritionItem $nutritionItem): self
    {
        return new self(
            id: $nutritionItem->id,
            foodItemId: $nutritionItem->foodItemId,
            quantity: $nutritionItem->quantity->value,
            carbs: $nutritionItem->carbs->value,
        );
    }
}
