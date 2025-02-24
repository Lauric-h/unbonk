<?php

namespace App\UI\Http\Rest\Food\View;

use App\Domain\Food\Entity\Brand;

final class BrandReadModel
{
    public function __construct(public string $id, public string $name)
    {
    }

    public static function fromBrand(Brand $brand): self
    {
        return new self(
            id: $brand->id,
            name: $brand->name,
        );
    }
}
