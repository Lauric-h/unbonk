<?php

namespace App\Application\NutritionPlan\UseCase\ListNutritionPlans;

use App\Domain\Shared\Bus\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ListNutritionPlansQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $runnerId,
    ) {
    }
}
