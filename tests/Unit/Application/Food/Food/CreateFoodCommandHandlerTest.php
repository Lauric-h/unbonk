<?php

namespace App\Tests\Unit\Application\Food\Food;

use App\Application\Food\UseCase\CreateFood\CreateFoodCommand;
use App\Application\Food\UseCase\CreateFood\CreateFoodCommandHandler;
use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Repository\BrandsCatalog;
use PHPUnit\Framework\TestCase;

final class CreateFoodCommandHandlerTest extends TestCase
{
    public function testCreateFood(): void
    {
        $command = new CreateFoodCommand(
            id: 1,
            brandId: 1,
            name: 'Food name',
            carbs: 10,
            ingestionType: 'solid',
            calories: 100
        );

        $repository = $this->createMock(BrandsCatalog::class);
        $handler = new CreateFoodCommandHandler($repository);

        $repository->expects($this->once())
            ->method('get')
            ->with($command->brandId)
            ->willReturn(new Brand(1, 'Brand name'))
        ;

        $repository->expects($this->once())
            ->method('add');

        ($handler)($command);
    }
}
