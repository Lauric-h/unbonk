<?php

namespace App\Tests\Unit\Infrastructure\NutritionPlan\Adapter;

use App\Domain\Food\DTO\FoodDTO;
use App\Domain\Race\DTO\ExternalNutritionItemDTO;
use App\Infrastructure\Food\Service\FoodAdapter;
use App\Infrastructure\NutritionPlan\Adapter\ExternalFoodAdapter;
use PHPUnit\Framework\TestCase;

final class ExternalFoodAdapterTest extends TestCase
{
    public function testGetById(): void
    {
        $foodService = $this->createMock(FoodAdapter::class);
        $foodAdapter = new ExternalFoodAdapter($foodService);

        $externalFoodDTO = new FoodDTO(
            id: 'id',
            name: 'name',
            carbs: 50,
            calories: 100,
        );

        $foodService->expects($this->once())
            ->method('getById')
            ->with('id')
            ->willReturn($externalFoodDTO);

        $expected = new ExternalNutritionItemDTO(
            reference: 'id',
            name: 'name',
            carbs: 50,
            calories: 100,
        );

        $actual = $foodAdapter->getById('id');

        $this->assertEquals($expected, $actual);
    }
}
