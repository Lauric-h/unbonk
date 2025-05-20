<?php

namespace App\UI\Http\Web\Food\Form\UpdateFood;

use App\Domain\Food\Entity\Food;
use App\Domain\Food\Entity\IngestionType;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateFoodModel
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
        #[Assert\Positive]
        public int $carbs,
        #[Assert\Choice(callback: [IngestionType::class, 'cases'])]
        public IngestionType $ingestionType,
        #[Assert\Positive]
        public ?int $calories,
    ) {
    }

    public static function fromFood(Food $food): self
    {
        return new self(
            $food->name,
            $food->carbs,
            $food->ingestionType,
            $food->calories,
        );
    }
}
