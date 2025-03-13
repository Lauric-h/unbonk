<?php

namespace App\Application\Food\UseCase\GetBrand;

use App\Domain\Food\Repository\BrandsCatalog;
use App\Infrastructure\Shared\Bus\QueryHandlerInterface;
use App\UI\Http\Rest\Food\View\BrandReadModel;

final readonly class GetBrandQueryHandler implements QueryHandlerInterface
{
    public function __construct(private BrandsCatalog $brandsCatalog)
    {
    }

    public function __invoke(GetBrandQuery $query): BrandReadModel
    {
        $brand = $this->brandsCatalog->get($query->id);

        return BrandReadModel::fromBrand($brand);
    }
}
