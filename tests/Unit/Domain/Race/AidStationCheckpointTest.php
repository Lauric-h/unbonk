<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\AidStationCheckpoint;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use PHPUnit\Framework\TestCase;

final class AidStationCheckpointTest extends TestCase
{
    public function testAidStationCheckpoint(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(42, 2000, 2000),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $checkpoint = new AidStationCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(120, 10, 1000, 1000),
            $race
        );

        $this->assertSame(CheckpointType::AidStation, $checkpoint->getCheckpointType());
    }

    public function testUpdate(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(42, 2000, 2000),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $checkpoint = new AidStationCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(120, 10, 1000, 1000),
            $race
        );

        $newMetrics = MetricsFromStart::create(300, 30, 2000, 2000);

        $checkpoint->update(
            'new name',
            'new location',
            $newMetrics
        );

        $this->assertSame('new name', $checkpoint->getName());
        $this->assertSame('new location', $checkpoint->getLocation());
        $this->assertSame(300, $checkpoint->getMetricsFromStart()->estimatedTimeInMinutes->minutes);
        $this->assertSame(30, $checkpoint->getMetricsFromStart()->distance->value);
        $this->assertSame(2000, $checkpoint->getMetricsFromStart()->ascent->value);
        $this->assertSame(2000, $checkpoint->getMetricsFromStart()->descent->value);
    }

    public function testValidateDistanceCannotBeMoreThanRace(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(42, 2000, 2000),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Invalid Checkpoint Distance: 43');

        $checkpoint = new AidStationCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(120, 43, 1000, 1000),
            $race
        );
    }

    public function testValidateDistanceCannotBeNegative(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(42, 2000, 2000),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Invalid Checkpoint Distance: 0');

        $checkpoint = new AidStationCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(120, 0, 1000, 1000),
            $race
        );
    }

    public function testValidateElevationGainCannotBeMoreThanRace(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(42, 2000, 2000),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Invalid Checkpoint elevation gain: 3000');

        $checkpoint = new AidStationCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(120, 10, 3000, 1000),
            $race
        );
    }

    public function testValidateElevationLossCannotBeMoreThanRace(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(42, 2000, 2000),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Invalid Checkpoint elevation loss: 3000');

        new AidStationCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(120, 10, 1000, 3000),
            $race
        );
    }

    public function testWillMetricsChangeIsTrue(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(42, 2000, 2000),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $checkpoint = new AidStationCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(120, 10, 1000, 1000),
            $race
        );

        $newMetrics = MetricsFromStart::create(300, 30, 2000, 2000);

        $this->assertTrue($checkpoint->willMetricsChange($newMetrics));
    }

    public function testWillMetricsChangeIsFalse(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(42, 2000, 2000),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $checkpoint = new AidStationCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(120, 10, 1000, 1000),
            $race
        );

        $newMetrics = MetricsFromStart::create(120, 10, 1000, 1000);

        $this->assertFalse($checkpoint->willMetricsChange($newMetrics));
    }
}
