<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\UpdateNutritionPlan;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateNutritionPlanCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $nutritionPlanId,
        #[Assert\Length(max: 255)]
        public ?string $name,
    ) {
    }
}
