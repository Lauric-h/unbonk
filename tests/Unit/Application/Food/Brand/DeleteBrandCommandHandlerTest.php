<?php

namespace App\Tests\Unit\Application\Food\Brand;

use App\Application\Food\UseCase\DeleteBrand\DeleteBrandCommand;
use App\Application\Food\UseCase\DeleteBrand\DeleteBrandCommandHandler;
use App\Domain\Food\Entity\Brand;
use App\Infrastructure\Food\Persistence\DoctrineBrandsCatalog;
use PHPUnit\Framework\TestCase;

class DeleteBrandCommandHandlerTest extends TestCase
{
    public function testDeleteBrand(): void
    {
        $id = 'id';
        $repository = $this->createMock(DoctrineBrandsCatalog::class);
        $handler = new DeleteBrandCommandHandler($repository);
        $command = new DeleteBrandCommand($id);

        $brand = new Brand('brand-id', 'brand-name');

        $repository->expects($this->once())
            ->method('get')
            ->with($id)
            ->willReturn($brand);

        $repository->expects($this->once())
            ->method('remove')
            ->with($brand);

        ($handler)($command);
    }
}
