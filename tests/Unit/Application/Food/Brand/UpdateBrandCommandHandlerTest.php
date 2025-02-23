<?php

namespace App\Tests\Unit\Application\Food\Brand;

use App\Application\Food\UpdateBrand\UpdateBrandCommand;
use App\Application\Food\UpdateBrand\UpdateBrandCommandHandler;
use App\Domain\Food\Entity\Brand;
use App\Infrastructure\Food\Persistence\DoctrineBrandsCatalog;
use PHPUnit\Framework\TestCase;

class UpdateBrandCommandHandlerTest extends TestCase
{
    public function testUpdateBrand(): void
    {
        $repository = $this->createMock(DoctrineBrandsCatalog::class);
        $handler = new UpdateBrandCommandHandler($repository);
        $command = new UpdateBrandCommand(
            id: 'brand-id',
            name: 'brand-name',
        );

        $beforeUpdateBrand = new Brand(
            id: 'brand-id',
            name: 'old-brand-name',
        );

        $expectedBrand = new Brand(
            id: 'brand-id',
            name: 'brand-name',
        );

        $repository->expects($this->once())
            ->method('get')
            ->with('brand-id')
            ->willReturn($beforeUpdateBrand);

        $repository->expects($this->once())
            ->method('add')
            ->with($expectedBrand);

        ($handler)($command);
    }
}
