<?php

namespace App\UI\Http\Web\Food\Form\AddFood;

use App\Domain\Food\Entity\IngestionType;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateFoodModel
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $name = null,
        #[Assert\Positive]
        public ?int $carbs = null,
        #[Assert\Choice(callback: [IngestionType::class, 'cases'])]
        public ?IngestionType $ingestionType = null,
        public ?int $calories = null,
    ) {
    }
}
