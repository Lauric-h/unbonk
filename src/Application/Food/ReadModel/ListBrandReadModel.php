<?php

namespace App\Application\Food\ReadModel;

use App\Domain\Food\Entity\Brand;

final class ListBrandReadModel
{
    /**
     * @param BrandReadModel[] $brands
     */
    public function __construct(public array $brands)
    {
    }

    /**
     * @param Brand[] $brands
     */
    public static function fromBrands(array $brands): self
    {
        return new self(
            brands: array_map(
                static fn ($brand) => BrandReadModel::fromBrand($brand),
                $brands
            )
        );
    }
}
