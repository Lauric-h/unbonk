<?php

namespace App\Tests\Unit\Application\Food\Brand;

use App\Application\Food\GetBrand\GetBrandQuery;
use App\Application\Food\GetBrand\GetBrandQueryHandler;
use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Repository\BrandsCatalog;
use PHPUnit\Framework\TestCase;

class GetBrandQueryHandlerTest extends TestCase
{
    public function testGetBrand(): void
    {
        $query = new GetBrandQuery('brand-id');
        $repository = $this->createMock(BrandsCatalog::class);
        $brand = new Brand('brand-id', 'brand-name');

        $repository->expects($this->once())
            ->method('get')
            ->with('brand-id')
            ->willReturn($brand);

        $handler = new GetBrandQueryHandler($repository);
        ($handler)($query);

        $this->assertSame('brand-id', $brand->id);
        $this->assertSame('brand-name', $brand->name);
    }
}
