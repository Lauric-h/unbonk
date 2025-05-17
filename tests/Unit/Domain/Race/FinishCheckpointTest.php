<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\FinishCheckpoint;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use PHPUnit\Framework\TestCase;

final class FinishCheckpointTest extends TestCase
{
    public function testFinishCheckpoint(): void
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

        $checkpoint = new FinishCheckpoint(
            'finishId',
            'name',
            'location',
            120,
            $race
        );

        $this->assertSame(CheckpointType::Finish, $checkpoint->getCheckpointType());
    }

    public function testFinishCheckpointUpdate(): void
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

        $checkpoint = new FinishCheckpoint(
            'finishId',
            'name',
            'location',
            120,
            $race
        );

        $checkpoint->update('updated', 'updatedLocation');

        $this->assertSame('updated', $checkpoint->getName());
        $this->assertSame('updatedLocation', $checkpoint->getLocation());
        $this->assertSame(2000, $checkpoint->getMetricsFromStart()->ascent->value);
        $this->assertSame(2000, $checkpoint->getMetricsFromStart()->descent->value);
        $this->assertSame(42, $checkpoint->getMetricsFromStart()->distance->value);
        $this->assertSame(120, $checkpoint->getMetricsFromStart()->estimatedTimeInMinutes->minutes);
    }

    public function testFinishCheckpointUpdateProfileMetrics(): void
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

        $checkpoint = new FinishCheckpoint(
            'finishId',
            'name',
            'location',
            120,
            $race
        );

        $profile = Profile::create(50, 3000, 3000);

        $checkpoint->updateProfileMetrics($profile);

        $this->assertSame(50, $checkpoint->getMetricsFromStart()->distance->value);
        $this->assertSame(3000, $checkpoint->getMetricsFromStart()->ascent->value);
        $this->assertSame(3000, $checkpoint->getMetricsFromStart()->descent->value);
        $this->assertSame(120, $checkpoint->getMetricsFromStart()->estimatedTimeInMinutes->minutes);
    }

    public function testWillMetricsChange(): void
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

        $checkpoint = new FinishCheckpoint(
            'finishId',
            'name',
            'location',
            120,
            $race
        );

        $this->assertFalse($checkpoint->willMetricsChange(MetricsFromStart::create(1, 1, 1, 1)));
    }
}
