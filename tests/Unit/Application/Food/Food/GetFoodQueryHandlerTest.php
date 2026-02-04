<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Food\Food;

use App\Application\Food\UseCase\GetFood\GetFoodQuery;
use App\Application\Food\UseCase\GetFood\GetFoodQueryHandler;
use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Entity\Food;
use App\Domain\Food\Entity\IngestionType;
use App\Infrastructure\Food\Persistence\DoctrineFoodsCatalog;
use PHPUnit\Framework\TestCase;

final class GetFoodQueryHandlerTest extends TestCase
{
    public function testGetFood(): void
    {
        $query = new GetFoodQuery('food-id');
        $repository = $this->createMock(DoctrineFoodsCatalog::class);
        $food = new Food(
            'food-id',
            new Brand('brand-id', 'brand-name'),
            'food-name',
            100,
            IngestionType::Liquid,
            100,
        );

        $repository->expects($this->once())
            ->method('get')
            ->with('food-id')
            ->willReturn($food);

        $handler = new GetFoodQueryHandler($repository);

        $actual = ($handler)($query);

        $this->assertSame('food-id', $actual->id);
        $this->assertSame('brand-name', $actual->brandName);
        $this->assertSame('food-name', $actual->name);
        $this->assertSame(100, $actual->carbs);
        $this->assertSame('liquid', $actual->ingestionType);
        $this->assertSame(100, $actual->calories);
    }
}
