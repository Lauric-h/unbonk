<?php

namespace App\Application\Food\UseCase\DeleteBrand;

use App\Domain\Food\Repository\BrandsCatalog;
use App\Infrastructure\Shared\Bus\CommandHandlerInterface;

final readonly class DeleteBrandCommandHandler implements CommandHandlerInterface
{
    public function __construct(private BrandsCatalog $brandsCatalog)
    {
    }

    public function __invoke(DeleteBrandCommand $command): void
    {
        $this->brandsCatalog->remove($command->id);
    }
}
