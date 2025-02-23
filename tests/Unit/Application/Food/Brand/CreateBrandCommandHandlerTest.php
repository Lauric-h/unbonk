<?php

namespace App\Tests\Unit\Application\Food\Brand;

use App\Application\Food\CreateBrand\CreateBrandCommand;
use App\Application\Food\CreateBrand\CreateBrandCommandHandler;
use App\Domain\Food\Entity\Brand;
use App\Infrastructure\Food\Persistence\DoctrineBrandsCatalog;
use PHPUnit\Framework\TestCase;

class CreateBrandCommandHandlerTest extends TestCase
{
    public function testCreateBrand(): void
    {
        $repository = $this->createMock(DoctrineBrandsCatalog::class);

        $command = new CreateBrandCommand(
            id: 'brand-id',
            name: 'brand-name',
        );

        $brand = new Brand(
            id: 'brand-id',
            name: 'brand-name',
        );

        $handler = new CreateBrandCommandHandler($repository);

        $repository->expects($this->once())
            ->method('add')
            ->with($brand);

        ($handler)($command);
    }
}
