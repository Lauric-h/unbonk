<?php

namespace App\Application\Food\UseCase\DeleteBrand;

use App\Domain\Food\Repository\BrandsCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class DeleteBrandCommandHandler implements CommandHandlerInterface
{
    public function __construct(private BrandsCatalog $brandsCatalog)
    {
    }

    public function __invoke(DeleteBrandCommand $command): void
    {
        $brand = $this->brandsCatalog->get($command->id);
        $this->brandsCatalog->remove($brand);
    }
}
