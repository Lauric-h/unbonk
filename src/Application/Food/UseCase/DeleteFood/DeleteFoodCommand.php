<?php

namespace App\Application\Food\UseCase\DeleteFood;

use App\Infrastructure\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final class DeleteFoodCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
    ) {
    }
}
