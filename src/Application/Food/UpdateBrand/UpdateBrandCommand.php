<?php

namespace App\Application\Food\UpdateBrand;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateBrandCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name,
    ) {
    }
}
