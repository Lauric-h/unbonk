<?php

namespace App\Tests\Unit\Application\Food\Brand;

use App\Application\Food\UseCase\CreateBrand\CreateBrandCommand;
use App\Application\Food\UseCase\CreateBrand\CreateBrandCommandHandler;
use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Exception\BrandAlreadyExistsException;
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
            ->method('exists')
            ->with('brand-name')
            ->willReturn(false);

        $repository->expects($this->once())
            ->method('add')
            ->with($brand);

        ($handler)($command);
    }

    public function testCreateBrandAlreadyExistsThrowsException(): void
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
            ->method('exists')
            ->with('brand-name')
            ->willReturn(true);

        $repository->expects($this->never())
            ->method('add')
            ->with($brand);

        $this->expectException(BrandAlreadyExistsException::class);
        $this->expectExceptionMessage('Brand with name "brand-name" already exists');

        ($handler)($command);
    }
}
