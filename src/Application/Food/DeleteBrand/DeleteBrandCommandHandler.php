<?php

namespace App\Application\Food\DeleteBrand;

use App\Domain\Food\Repository\BrandsCatalog;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class DeleteBrandCommandHandler
{
    public function __construct(private BrandsCatalog $brandsCatalog)
    {
    }

    public function __invoke(DeleteBrandCommand $command): void
    {
        $this->brandsCatalog->remove($command->id);
    }
}
