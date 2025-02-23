<?php

namespace App\Application\Food\UpdateBrand;

use App\Domain\Food\Repository\BrandsCatalog;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class UpdateBrandCommandHandler
{
    public function __construct(private BrandsCatalog $brandsCatalog)
    {
    }

    public function __invoke(UpdateBrandCommand $command): void
    {
        $brand = $this->brandsCatalog->get($command->id);
        $brand->update($command->name);
        $this->brandsCatalog->add($brand);
    }
}
