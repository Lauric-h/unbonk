<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Food\Service;

use App\Application\Food\UseCase\GetFood\GetFoodQuery;
use App\Domain\Food\DTO\FoodDTO;
use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Entity\Food;
use App\Domain\Food\Entity\IngestionType;
use App\Infrastructure\Food\Service\FoodAdapter;
use App\Infrastructure\Shared\Bus\QueryBus;
use PHPUnit\Framework\TestCase;

final class FoodServiceTest extends TestCase
{
    public function testGetById(): void
    {
        $queryBus = $this->createMock(QueryBus::class);
        $foodService = new FoodAdapter($queryBus);

        $food = new Food(
            'id',
            new Brand('id', 'brand name'),
            'name',
            100,
            IngestionType::Liquid,
            300
        );

        $queryBus->expects($this->once())
            ->method('query')
            ->with(new GetFoodQuery('id'))
            ->willReturn($food);

        $expected = new FoodDTO(
            'id',
            'name',
            100,
            300
        );

        $actual = $foodService->getById('id');

        $this->assertEquals($expected, $actual);
    }
}
