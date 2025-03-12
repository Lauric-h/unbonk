<?php

namespace App\Application\Food\UseCase\UpdateBrand;

use App\Domain\Food\Repository\BrandsCatalog;
use App\Infrastructure\Shared\Bus\CommandHandlerInterface;

final readonly class UpdateBrandCommandHandler implements CommandHandlerInterface
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
