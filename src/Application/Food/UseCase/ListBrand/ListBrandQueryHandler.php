<?php

namespace App\Application\Food\UseCase\ListBrand;

use App\Domain\Food\Repository\BrandsCatalog;
use App\Infrastructure\Shared\Bus\QueryHandlerInterface;
use App\UI\Http\Rest\Food\View\ListBrandReadModel;

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
