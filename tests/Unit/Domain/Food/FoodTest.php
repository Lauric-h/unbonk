<?php

namespace App\Tests\Unit\Domain\Food;

use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Entity\Food;
use App\Domain\Food\Entity\IngestionType;
use App\Domain\Food\Exception\FoodCaloriesMustBePositiveException;
use App\Domain\Food\Exception\FoodCarbsMustBePositiveException;
use PHPUnit\Framework\TestCase;

final class FoodTest extends TestCase
{
    public function testUpdateNegativeCarbsThrowsException(): void
    {
        $food = new Food(
            'id',
            new Brand('id', 'brand name'),
            'name',
            100,
            IngestionType::Liquid,
            300
        );

        $this->expectException(FoodCarbsMustBePositiveException::class);

        $food->update('name updated', -10, IngestionType::Liquid, null);
    }

    public function testUpdateNegativeCaloriesThrowsException(): void
    {
        $food = new Food(
            'id',
            new Brand('id', 'brand name'),
            'name',
            100,
            IngestionType::Liquid,
            300
        );

        $this->expectException(FoodCaloriesMustBePositiveException::class);

        $food->update('name updated', 10, IngestionType::Liquid, -10);
    }

    public function testUpdateWithCorrectValues(): void
    {
        $food = new Food(
            'id',
            new Brand('id', 'brand name'),
            'name',
            100,
            IngestionType::Liquid,
            300
        );

        $food->update('name updated', 10, IngestionType::Solid, 200);

        $this->assertSame('name updated', $food->name);
        $this->assertSame(10, $food->carbs);
        $this->assertSame(200, $food->calories);
        $this->assertSame(IngestionType::Solid, $food->ingestionType);
    }
}
