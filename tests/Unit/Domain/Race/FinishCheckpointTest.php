<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\FinishCheckpoint;
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
            new Profile(42, 2000, 2000),
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
            new Profile(42, 2000, 2000),
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
        $this->assertSame(2000, $checkpoint->getMetricsFromStart()->elevationGain);
        $this->assertSame(2000, $checkpoint->getMetricsFromStart()->elevationLoss);
        $this->assertSame(42, $checkpoint->getMetricsFromStart()->distance);
        $this->assertSame(120, $checkpoint->getMetricsFromStart()->estimatedTimeInMinutes);
    }

    public function testFinishCheckpointUpdateProfileMetrics(): void
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

        $checkpoint = new FinishCheckpoint(
            'finishId',
            'name',
            'location',
            120,
            $race
        );

        $profile = new Profile(50, 3000, 3000);

        $checkpoint->updateProfileMetrics($profile);

        $this->assertSame(50, $checkpoint->getMetricsFromStart()->distance);
        $this->assertSame(3000, $checkpoint->getMetricsFromStart()->elevationGain);
        $this->assertSame(3000, $checkpoint->getMetricsFromStart()->elevationLoss);
        $this->assertSame(120, $checkpoint->getMetricsFromStart()->estimatedTimeInMinutes);
    }
}
