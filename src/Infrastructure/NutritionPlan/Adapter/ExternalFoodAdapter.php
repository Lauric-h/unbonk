<?php

namespace App\Infrastructure\NutritionPlan\Adapter;

use App\Domain\Race\DTO\ExternalNutritionItemDTO;
use App\Domain\Race\Port\ExternalFoodPort;
use App\Infrastructure\Food\Service\FoodAdapter;

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
