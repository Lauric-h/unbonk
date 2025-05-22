<?php

namespace App\UI\Http\Web\Food\Form\CreateBrand;

use Symfony\Component\Validator\Constraints as Assert;

final class CreateBrandModel
{
    public function __construct(
        #[Assert\NotBlank]
        public ?string $name = null,
    ) {
    }
}
