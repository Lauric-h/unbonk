<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Exception\DistanceCannotBeNegativeException;
use App\Domain\Race\Exception\ElevationValueCannotBeNegativeException;
use PHPUnit\Framework\TestCase;

final class ProfileTest extends TestCase
{
    public function testElevationGainCannotBeNegative(): void
    {
        $this->expectException(ElevationValueCannotBeNegativeException::class);
        $this->expectExceptionMessage('Field elevationGain value cannot be negative, got -10');

        new Profile(100, -10, 100);
    }

    public function testElevationLossCannotBeNegative(): void
    {
        $this->expectException(ElevationValueCannotBeNegativeException::class);
        $this->expectExceptionMessage('Field elevationLoss value cannot be negative, got -10');

        new Profile(100, 100, -10);
    }

    public function testDistanceCannotBeNegative(): void
    {
        $this->expectException(DistanceCannotBeNegativeException::class);
        $this->expectExceptionMessage('Race distance cannot be negative, got -10');

        new Profile(-10, 100, 100);
    }
}
