<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Form\NutritionPlan;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateNutritionPlanModel
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $name = null
    ) {
    }
}
