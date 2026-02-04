<?php

namespace App\Application\NutritionPlan\UseCase\CreateNutritionPlan;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateNutritionPlanCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $externalRaceId,
        #[Assert\Uuid]
        public string $runnerId,
    ) {
    }
}
