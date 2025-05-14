<?php

namespace App\Application\NutritionPlan\UseCase\DeleteNutritionPlan;

use App\Domain\Shared\Bus\CommandInterface;
use App\Infrastructure\Shared\Bus\UserAwareInterface;
use App\Infrastructure\Shared\Bus\UserAwareTrait;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteNutritionPlanCommand implements CommandInterface, UserAwareInterface
{
    use UserAwareTrait;

    public function __construct(
        #[Assert\Uuid]
        public string $id,
    ) {
    }
}
