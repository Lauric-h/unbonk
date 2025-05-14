<?php

namespace App\Application\NutritionPlan\UseCase\GetNutritionPlan;

use App\Domain\Shared\Bus\QueryInterface;
use App\Infrastructure\Shared\Bus\UserAwareInterface;
use App\Infrastructure\Shared\Bus\UserAwareTrait;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetNutritionPlanQuery implements QueryInterface, UserAwareInterface
{
    use UserAwareTrait;

    public function __construct(
        #[Assert\Uuid]
        public string $id,
    ) {
    }
}
