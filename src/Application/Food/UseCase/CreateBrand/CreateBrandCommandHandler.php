<?php

namespace App\Application\Food\UseCase\CreateBrand;

use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Exception\BrandAlreadyExistsException;
use App\Domain\Food\Repository\BrandsCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class CreateBrandCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private BrandsCatalog $brandsCatalog,
    ) {
    }

    public function __invoke(CreateBrandCommand $createBrand): void
    {
        if ($this->brandsCatalog->exists($createBrand->name)) {
            throw new BrandAlreadyExistsException($createBrand->name);
        }

        $brand = new Brand(
            id: $createBrand->id,
            name: $createBrand->name,
        );

        $this->brandsCatalog->add($brand);
    }
}
