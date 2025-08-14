<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\AidStationCheckpoint;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use PHPUnit\Framework\TestCase;

final class AidStationCheckpointTest extends TestCase
{
    public function testAidStationCheckpoint(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $checkpoint = new AidStationCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(new Duration(120), new Distance(10), new Ascent(1000), new Descent(1000)),
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
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $checkpoint = new AidStationCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(new Duration(120), new Distance(10), new Ascent(1000), new Descent(1000)),
            $race
        );

        $newMetrics = MetricsFromStart::create(new Duration(300), new Distance(30), new Ascent(2000), new Descent(2000));

        $checkpoint->update(
            'new name',
            'new location',
            $newMetrics
        );

        $this->assertSame('new name', $checkpoint->getName());
        $this->assertSame('new location', $checkpoint->getLocation());
        $this->assertSame(300, $checkpoint->getMetricsFromStart()->estimatedTimeInMinutes);
        $this->assertSame(30, $checkpoint->getMetricsFromStart()->distance);
        $this->assertSame(2000, $checkpoint->getMetricsFromStart()->ascent);
        $this->assertSame(2000, $checkpoint->getMetricsFromStart()->descent);
    }

    public function testValidateDistanceCannotBeMoreThanRace(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
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
            MetricsFromStart::create(new Duration(120), new Distance(43), new Ascent(1000), new Descent(1000)),
            $race
        );
    }

    public function testValidateDistanceCannotBeNegative(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
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
            MetricsFromStart::create(new Duration(120), new Distance(0), new Ascent(1000), new Descent(1000)),
            $race
        );
    }

    public function testValidateElevationGainCannotBeMoreThanRace(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
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
            MetricsFromStart::create(new Duration(120), new Distance(10), new Ascent(3000), new Descent(1000)),
            $race
        );
    }

    public function testValidateElevationLossCannotBeMoreThanRace(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
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
            MetricsFromStart::create(new Duration(120), new Distance(10), new Ascent(1000), new Descent(3000)),
            $race
        );
    }

    public function testWillMetricsChangeIsTrue(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $checkpoint = new AidStationCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(new Duration(120), new Distance(10), new Ascent(1000), new Descent(1000)),
            $race
        );

        $newMetrics = MetricsFromStart::create(new Duration(300), new Distance(30), new Ascent(2000), new Descent(2000));

        $this->assertTrue($checkpoint->willMetricsChange($newMetrics));
    }

    public function testWillMetricsChangeIsFalse(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $checkpoint = new AidStationCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(new Duration(120), new Distance(10), new Ascent(1000), new Descent(1000)),
            $race
        );

        $newMetrics = MetricsFromStart::create(new Duration(120), new Distance(10), new Ascent(1000), new Descent(1000));

        $this->assertFalse($checkpoint->willMetricsChange($newMetrics));
    }
}
