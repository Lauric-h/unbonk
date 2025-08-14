<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\Profile;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use PHPUnit\Framework\TestCase;

final class ProfileTest extends TestCase
{
    public function testCreate(): void
    {
        $profile = Profile::create(new Distance(100), new Ascent(10), new Descent(100));

        $this->assertSame(100, $profile->distance);
        $this->assertSame(10, $profile->ascent);
        $this->assertSame(100, $profile->descent);
    }

    public function testAscentCannotBeNegative(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Ascent cannot be negative: -10');

        Profile::create(new Distance(100), new Ascent(-10), new Descent(100));
    }

    public function testDescentCannotBeNegative(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Descent cannot be negative: -10');

        Profile::create(new Distance(100), new Ascent(100), new Descent(-10));
    }

    public function testDistanceCannotBeNegative(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Distance cannot be negative: -10');

        Profile::create(new Distance(-10), new Ascent(100), new Descent(100));
    }
}
