<?php

namespace App\Infrastructure\NutritionPlan\Adapter;

use App\Application\NutritionPlan\Port\ExternalFoodPort;
use App\Domain\NutritionPlan\DTO\ExternalNutritionItemDTO;
use App\Infrastructure\Food\Service\FoodAdapter;

// @TODO RENAME AS CATALOG
final readonly class ExternalFoodAdapter implements ExternalFoodPort
{
    public function __construct(private FoodAdapter $externalFoodService)
    {
    }

    public function getById(string $id): ExternalNutritionItemDTO
    {
        $externalFoodDTO = $this->externalFoodService->getById($id);

        return new ExternalNutritionItemDTO(
            $externalFoodDTO->id,
            $externalFoodDTO->name,
            $externalFoodDTO->carbs,
            $externalFoodDTO->calories,
        );
    }
}
