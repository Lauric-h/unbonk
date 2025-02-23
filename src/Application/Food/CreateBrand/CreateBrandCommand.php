<?php

namespace App\Application\Food\CreateBrand;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateBrandCommand
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
        #[Assert\NotBlank]
        public string $name,
    ) {
    }
}
