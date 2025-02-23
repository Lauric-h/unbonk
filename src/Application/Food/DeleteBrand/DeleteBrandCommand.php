<?php

namespace App\Application\Food\DeleteBrand;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class DeleteBrandCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
    ) {
    }
}
