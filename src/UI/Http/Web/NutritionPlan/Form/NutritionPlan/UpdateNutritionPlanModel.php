<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\Form\NutritionPlan;

use Symfony\Component\Validator\Constraints as Assert;

final class UpdateNutritionPlanModel
{
    public function __construct(
        #[Assert\Length(max: 255)]
        public ?string $name = null,
    ) {
    }
}
