<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Food\Food;

use App\Application\Food\ReadModel\FoodReadModel;
use App\Application\Food\ReadModel\ListFoodReadModel;
use App\Application\Food\UseCase\ListFood\ListFoodQuery;
use App\Application\Food\UseCase\ListFood\ListFoodQueryHandler;
use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Entity\Food;
use App\Domain\Food\Entity\IngestionType;
use App\Domain\Food\Repository\FoodsCatalog;
use PHPUnit\Framework\TestCase;

final class ListFoodQueryHandlerTest extends TestCase
{
    public function testListAllFood(): void
    {
        $repository = $this->createMock(FoodsCatalog::class);
        $handler = new ListFoodQueryHandler($repository);
        $brand = new Brand('brand-id', 'brand-name');

        $foods = [];
        for ($i = 0; $i < 5; ++$i) {
            $foods[] = new Food(
                id: 'id'.$i,
                brand: $brand,
                name: 'name'.$i,
                carbs: $i + 1,
                ingestionType: IngestionType::Liquid,
                calories: $i + 1
            );
        }

        $repository->expects($this->once())
            ->method('getAll')
            ->willReturn($foods);

        $readModels = [];
        for ($i = 0; $i < 5; ++$i) {
            $readModels[] = new FoodReadModel(
                id: 'id'.$i,
                brandName: 'brand-name',
                name: 'name'.$i,
                carbs: $i + 1,
                ingestionType: IngestionType::Liquid->value,
                calories: $i + 1
            );
        }

        $expected = new ListFoodReadModel($readModels);

        $actual = ($handler)(new ListFoodQuery('brand-id'));

        $this->assertCount(5, $actual->foods);
        $this->assertContainsOnlyInstancesOf(FoodReadModel::class, $actual->foods);

        foreach ($actual->foods as $key => $food) {
            $this->assertSame($expected->foods[$key]->id, $food->id);
            $this->assertSame($expected->foods[$key]->name, $food->name);
            $this->assertSame($expected->foods[$key]->carbs, $food->carbs);
            $this->assertSame($expected->foods[$key]->calories, $food->calories);
            $this->assertSame($expected->foods[$key]->ingestionType, $food->ingestionType);
        }
    }
}
