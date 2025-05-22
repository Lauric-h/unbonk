<?php

namespace App\UI\Http\Web\Food\Form\UpdateBrand;

use App\Domain\Food\Entity\Brand;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateBrandModel
{
    public function __construct(
        #[Assert\NotBlank]
        public string $name,
    ) {
    }

    public static function fromBrand(Brand $brand): self
    {
        return new self($brand->name);
    }
}
