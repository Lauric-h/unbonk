<?php

namespace App\Application\NutritionPlan\UseCase\DeleteNutritionItem;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteNutritionItemCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $nutritionPlanId,
        #[Assert\Uuid]
        public string $segmentId,
        #[Assert\Uuid]
        public string $nutritionItemId,
    ) {
    }
}
