<?php

namespace App\Application\Food\UseCase\CreateFood;

use App\Domain\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateFoodCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\Uuid]
        public string $brandId,
        #[Assert\NotBlank]
        public string $name,
        #[Assert\Positive]
        public int $carbs,
        public string $ingestionType,
        public ?int $calories,
    ) {
    }
}
