<?php

namespace App\Application\Food\UseCase\DeleteBrand;

use App\Infrastructure\Shared\Bus\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteBrandCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
    ) {
    }
}
