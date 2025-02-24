<?php

namespace App\Tests\Unit\UI\Food;

use App\Domain\Food\Entity\Brand;
use App\UI\Http\Rest\Food\View\BrandReadModel;
use PHPUnit\Framework\TestCase;

class BrandReadModelTest extends TestCase
{
    public function testFromBrand(): void
    {
        $brand = new Brand('id', 'name');
        $expected = new BrandReadModel('id', 'name');

        $actual = BrandReadModel::fromBrand($brand);

        $this->assertEquals($expected, $actual);
    }
}
