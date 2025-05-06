<?php

namespace App\Application\NutritionPlan\UseCase\UpdateNutritionItemQuantity;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateNutritionItemQuantityCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $segmentId,
        #[Assert\Uuid]
        public string $nutritionItemId,
        #[Assert\PositiveOrZero]
        public int $quantity,
    ) {
    }
}
