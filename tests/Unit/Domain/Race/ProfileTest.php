<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\Profile;
use PHPUnit\Framework\TestCase;

final class ProfileTest extends TestCase
{
    public function testCreate(): void
    {
        $profile = Profile::create(100, 10, 100);

        $this->assertSame(100, $profile->distance->value);
        $this->assertSame(10, $profile->ascent->value);
        $this->assertSame(100, $profile->descent->value);
    }

    public function testAscentCannotBeNegative(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Ascent cannot be negative: -10');

        Profile::create(100, -10, 100);
    }

    public function testDescentCannotBeNegative(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Descent cannot be negative: -10');

        Profile::create(100, 100, -10);
    }

    public function testDistanceCannotBeNegative(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Distance cannot be negative: -10');

        Profile::create(-10, 100, 100);
    }
}
