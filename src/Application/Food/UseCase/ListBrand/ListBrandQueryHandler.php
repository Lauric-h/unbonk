<?php

namespace App\Application\Food\UseCase\ListBrand;

use App\Application\Food\ReadModel\ListBrandReadModel;
use App\Domain\Food\Repository\BrandsCatalog;
use App\Domain\Shared\Bus\QueryHandlerInterface;

final readonly class ListBrandQueryHandler implements QueryHandlerInterface
{
    public function __construct(private BrandsCatalog $brandsCatalog)
    {
    }

    public function __invoke(ListBrandQuery $query): ListBrandReadModel
    {
        $brands = $this->brandsCatalog->getAll();

        return ListBrandReadModel::fromBrands($brands);
    }
}
