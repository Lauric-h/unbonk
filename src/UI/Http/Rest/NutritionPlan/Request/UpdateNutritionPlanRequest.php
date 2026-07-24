<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\NutritionPlan\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateNutritionPlanRequest
{
    public function __construct(
        #[Assert\Length(max: 255)]
        #[Assert\NotBlank]
        public ?string $name = null,
    ) {
    }
}
