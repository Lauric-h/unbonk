<?php

namespace App\Tests\Unit\Domain\Food;

use App\Domain\Food\Entity\Brand;
use App\Domain\Food\Event\BrandCreated;
use PHPUnit\Framework\TestCase;

class BrandTest extends TestCase
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
}
