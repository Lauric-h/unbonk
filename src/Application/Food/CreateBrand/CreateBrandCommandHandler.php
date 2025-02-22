<?php

namespace App\Application\Food\CreateBrand;

use App\Domain\Food\Brand;
use App\Domain\Food\BrandsCatalog;
use App\SharedKernel\IdGenerator;

final readonly class CreateBrandCommandHandler
{
    public function __construct(
        private BrandsCatalog $brandsCatalog,
        private IdGenerator $idGenerator,
    ) {
    }

    public function __invoke(CreateBrandCommand $createBrand): void
    {
        $brand = new Brand(
            id: $this->idGenerator->generate(),
            name: $createBrand->name,
        );

        $this->brandsCatalog->add($brand);
    }
}