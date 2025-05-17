<?php

namespace App\Application\NutritionPlan\UseCase\AddNutritionItem;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class AddNutritionItemCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $externalFoodId,
        #[Assert\Uuid]
        public string $nutritionPlanId,
        #[Assert\Uuid]
        public string $segmentId,
        #[Assert\Positive]
        public int $quantity,
    ) {
    }
}
