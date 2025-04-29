<?php

namespace App\Tests\Unit\UI\Food;

use App\Application\Food\ReadModel\BrandReadModel;
use App\Domain\Food\Entity\Brand;
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
