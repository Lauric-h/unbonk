<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Domain\Race\Entity\StartCheckpoint;
use PHPUnit\Framework\TestCase;

final class StartCheckpointTest extends TestCase
{
    public function testStartCheckpoint(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            new Profile(42, 2000, 2000),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $checkpoint = new StartCheckpoint(
            'startId',
            'name',
            'location',
            $race
        );

        $this->assertSame(CheckpointType::Start, $checkpoint->getCheckpointType());
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->elevationGain);
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->elevationLoss);
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->distance);
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->estimatedTimeInMinutes);
    }

    public function testStartCheckpointUpdate(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            new Profile(42, 2000, 2000),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $checkpoint = new StartCheckpoint(
            'startId',
            'name',
            'location',
            $race
        );

        $checkpoint->update('updated', 'updatedLocation');

        $this->assertSame('updated', $checkpoint->getName());
        $this->assertSame('updatedLocation', $checkpoint->getLocation());
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->elevationGain);
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->elevationLoss);
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->distance);
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->estimatedTimeInMinutes);
    }

    public function testWillMetricsChange(): void
    {
        $race = Race::create(
            'id',
            new \DateTimeImmutable(),
            'name',
            new Profile(42, 2000, 2000),
            new Address('city', '74xxx'),
            'runnerId',
            'startId',
            'finishId'
        );

        $checkpoint = new StartCheckpoint(
            'startId',
            'name',
            'location',
            $race
        );

        $this->assertFalse($checkpoint->willMetricsChange(new MetricsFromStart(1, 1, 1, 1)));
    }
}
