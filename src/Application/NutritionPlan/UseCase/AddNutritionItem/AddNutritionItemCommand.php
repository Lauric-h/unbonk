<?php

namespace App\Application\NutritionPlan\UseCase\AddNutritionItem;

use App\Domain\Shared\Bus\CommandInterface;
use App\Infrastructure\Shared\Bus\UserAwareInterface;
use App\Infrastructure\Shared\Bus\UserAwareTrait;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class AddNutritionItemCommand implements CommandInterface, UserAwareInterface
{
    use UserAwareTrait;

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
