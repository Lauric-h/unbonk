<?php

namespace App\Application\Food\UseCase\UpdateBrand;

use App\Domain\Food\Exception\BrandAlreadyExistsException;
use App\Domain\Food\Repository\BrandsCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

final readonly class UpdateBrandCommandHandler implements CommandHandlerInterface
{
    public function __construct(private BrandsCatalog $brandsCatalog)
    {
    }

    public function __invoke(UpdateBrandCommand $command): void
    {
        try {
            $brand = $this->brandsCatalog->get($command->id);
            $brand->update($command->name);
            $this->brandsCatalog->add($brand);
        } catch (UniqueConstraintViolationException $exception) {
            throw new BrandAlreadyExistsException($command->name);
        }
    }
}
