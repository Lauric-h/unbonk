<?php

namespace App\Application\Food\CreateBrand;

use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Repository\BrandsCatalog;
use App\Infrastructure\Shared\Bus\CommandHandlerInterface;

final readonly class CreateBrandCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private BrandsCatalog $brandsCatalog,
    ) {
    }

    public function __invoke(CreateBrandCommand $createBrand): void
    {
        $brand = new Brand(
            id: $createBrand->id,
            name: $createBrand->name,
        );

        $this->brandsCatalog->add($brand);
    }
}
