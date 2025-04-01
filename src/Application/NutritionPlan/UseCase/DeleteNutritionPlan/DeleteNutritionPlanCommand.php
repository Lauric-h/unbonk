<?php

namespace App\Application\NutritionPlan\UseCase\DeleteNutritionPlan;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteNutritionPlanCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
    ) {
    }
}
