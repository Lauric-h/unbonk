<?php

namespace App\Tests\Unit\Application\Food\Food;

use App\Application\Food\UseCase\DeleteFood\DeleteFoodCommand;
use App\Application\Food\UseCase\DeleteFood\DeleteFoodCommandHandler;
use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Entity\Food;
use App\Domain\Food\Entity\IngestionType;
use App\Infrastructure\Food\Persistence\DoctrineFoodsCatalog;
use PHPUnit\Framework\TestCase;

final class DeleteFoodCommandHandlerTest extends TestCase
{
    public function testDeleteFood(): void
    {
        $id = 'id';
        $repository = $this->createMock(DoctrineFoodsCatalog::class);
        $handler = new DeleteFoodCommandHandler($repository);
        $command = new DeleteFoodCommand($id);

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
            ->with($id)
            ->willReturn($food);

        $repository->expects($this->once())
            ->method('remove')
            ->with($food);

        ($handler)($command);
    }
}
