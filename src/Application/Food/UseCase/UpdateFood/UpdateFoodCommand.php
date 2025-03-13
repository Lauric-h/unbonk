<?php

namespace App\Application\Food\UseCase\UpdateFood;

use App\Domain\Food\Entity\IngestionType;
use App\Infrastructure\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateFoodCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name,
        #[Assert\Positive]
        public int $carbs,
        #[Assert\Choice(callback: [IngestionType::class, 'cases'])]
        public string $ingestionType,
        #[Assert\Positive]
        public ?int $calories,
    ) {
    }
}
