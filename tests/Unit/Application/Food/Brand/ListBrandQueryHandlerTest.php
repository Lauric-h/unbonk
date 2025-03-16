<?php

namespace App\Tests\Unit\Application\Food\Brand;

use App\Application\Food\UseCase\ListBrand\ListBrandQuery;
use App\Application\Food\UseCase\ListBrand\ListBrandQueryHandler;
use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Repository\BrandsCatalog;
use App\UI\Http\Rest\Food\View\BrandReadModel;
use App\UI\Http\Rest\Food\View\ListBrandReadModel;
use PHPUnit\Framework\TestCase;

final class ListBrandQueryHandlerTest extends TestCase
{
    public function testListBrand(): void
    {
        $repository = $this->createMock(BrandsCatalog::class);
        $handler = new ListBrandQueryHandler($repository);

        $brands = [];
        for ($i = 0; $i < 5; ++$i) {
            $brands[] = new Brand('id'.$i, 'name'.$i);
        }

        $repository->expects($this->once())
            ->method('getAll')
            ->willReturn($brands);

        $expected = new ListBrandReadModel([]);
        for ($i = 0; $i < 5; ++$i) {
            $expected->brands[] = new BrandReadModel('id'.$i, 'name'.$i);
        }

        $actual = ($handler)(new ListBrandQuery());

        $this->assertCount(5, $actual->brands);
        $this->assertContainsOnlyInstancesOf(BrandReadModel::class, $actual->brands);

        foreach ($actual->brands as $key => $brand) {
            $this->assertSame($expected->brands[$key]->id, $brand->id);
            $this->assertSame($expected->brands[$key]->name, $brand->name);
        }
    }
}
