<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Food;

use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Entity\Food;
use App\Domain\Food\Entity\IngestionType;
use App\Domain\Food\Event\BrandCreated;
use PHPUnit\Framework\TestCase;

final class BrandTest extends TestCase
{
    public function testRecordAndPullEvents(): void
    {
        $brand = new Brand('id', 'name');

        $brandCreated = new BrandCreated('id');
        $brand->recordEvent($brandCreated);

        $events = $brand->pullEvents();

        $this->assertCount(2, $events);
        $this->assertInstanceOf(BrandCreated::class, $events[0]);
        $this->assertInstanceOf(BrandCreated::class, $events[1]);
    }

    public function testConstructorRecordEvent(): void
    {
        $brand = new Brand('id', 'name');

        $events = $brand->pullEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(BrandCreated::class, $events[0]);
    }

    public function testUpdate(): void
    {
        $brand = new Brand('id', 'name');

        $brand->update('new name');
        $this->assertSame('new name', $brand->name);
    }

    public function testAddFood(): void
    {
        $brand = new Brand('id', 'name');
        $food = new Food('id', $brand, 'name', 10, IngestionType::Liquid, 10);

        $brand->addFood($food);

        $this->assertCount(1, $brand->foods);
        $this->assertSame($brand, $food->brand);
    }

    public function testAddFoodWithExistingFoodDoesNothing(): void
    {
        $brand = new Brand('id', 'name');
        $food = new Food('id', $brand, 'name', 10, IngestionType::Liquid, 10);
        $food2 = new Food('id2', $brand, 'name2', 20, IngestionType::Liquid, 20);
        $brand->foods->add($food);
        $brand->foods->add($food2);

        $brand->addFood($food);

        $this->assertCount(2, $brand->foods);
    }
}
