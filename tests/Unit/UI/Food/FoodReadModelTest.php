<?php

namespace App\Tests\Unit\UI\Food;

use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Entity\Food;
use App\Domain\Food\Entity\IngestionType;
use App\UI\Http\Rest\Food\View\FoodReadModel;
use PHPUnit\Framework\TestCase;

final class FoodReadModelTest extends TestCase
{
    public function testFromFood(): void
    {
        $food = new Food(
            'food-id',
            new Brand('brand-id', 'brand-name'),
            'food-name',
            100,
            IngestionType::Liquid,
            100,
        );
        $expected = new FoodReadModel(
            'food-id',
            'brand-name',
            'food-name',
            100,
            'liquid',
            100
        );

        $actual = FoodReadModel::fromFood($food);

        $this->assertEquals($expected, $actual);
    }
}
