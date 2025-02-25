<?php

namespace App\Application\Food\CreateFood;

use App\Infrastructure\Shared\Bus\CommandInterface;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateFoodCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name,
    ) {
    }
}