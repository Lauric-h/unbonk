<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\CreateNutritionPlan;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateNutritionPlanCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $nutritionPlanId,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $importedRaceId,
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $runnerId,
        public ?string $name = null,
    ) {
    }
}
