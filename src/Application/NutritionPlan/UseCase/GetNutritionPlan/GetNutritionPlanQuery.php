<?php

namespace App\Application\NutritionPlan\UseCase\GetNutritionPlan;

use App\Domain\Shared\Bus\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetNutritionPlanQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
    ) {
    }
}
