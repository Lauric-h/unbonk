<?php

namespace App\Tests\Unit\Application\Food\Food;

use App\Application\Food\UseCase\UpdateFood\UpdateFoodCommand;
use App\Application\Food\UseCase\UpdateFood\UpdateFoodCommandHandler;
use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Entity\Food;
use App\Domain\Food\Entity\IngestionType;
use App\Infrastructure\Food\Persistence\DoctrineFoodsCatalog;
use PHPUnit\Framework\TestCase;

final class UpdateFoodCommandHandlerTest extends TestCase
{
    public function testUpdateFood(): void
    {
        $repository = $this->createMock(DoctrineFoodsCatalog::class);
        $handler = new UpdateFoodCommandHandler($repository);
        $command = new UpdateFoodCommand(
            id: 'food-id',
            name: 'food-name updated',
            carbs: 100,
            calories: 100,
            ingestionType: IngestionType::SemiLiquid,
        );

        $beforeUpdateFood = new Food(
            id: 'food-id',
            brand: new Brand('brand-id', 'brand-name'),
            name: 'food-name',
            carbs: 200,
            calories: 200,
            ingestionType: IngestionType::Liquid,
        );

        $expectedFood = new Food(
            id: 'food-id',
            brand: new Brand('brand-id', 'brand-name'),
            name: 'food-name updated',
            carbs: 100,
            calories: 100,
            ingestionType: IngestionType::SemiLiquid,
        );

        $repository->expects($this->once())
            ->method('get')
            ->with('food-id')
            ->willReturn($beforeUpdateFood);

        $repository->expects($this->once())
            ->method('add')
            ->with($expectedFood);

        ($handler)($command);
    }
}
