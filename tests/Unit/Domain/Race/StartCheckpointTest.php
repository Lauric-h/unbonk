<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Domain\Race\Entity\StartCheckpoint;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;
use PHPUnit\Framework\TestCase;

final class StartCheckpointTest extends TestCase
{
    public function testStartCheckpoint(): void
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

        $checkpoint = new StartCheckpoint(
            'startId',
            'name',
            'location',
            $race
        );

        $this->assertSame(CheckpointType::Start, $checkpoint->getCheckpointType());
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->ascent);
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->descent);
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->distance);
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->estimatedTimeInMinutes);
    }

    public function testStartCheckpointUpdate(): void
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

        $checkpoint = new StartCheckpoint(
            'startId',
            'name',
            'location',
            $race
        );

        $checkpoint->update('updated', 'updatedLocation');

        $this->assertSame('updated', $checkpoint->getName());
        $this->assertSame('updatedLocation', $checkpoint->getLocation());
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->ascent);
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->descent);
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->distance);
        $this->assertSame(0, $checkpoint->getMetricsFromStart()->estimatedTimeInMinutes);
    }

    public function testWillMetricsChange(): void
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

        $checkpoint = new StartCheckpoint(
            'startId',
            'name',
            'location',
            $race
        );

        $this->assertFalse($checkpoint->willMetricsChange(MetricsFromStart::create(new Duration(1), new Distance(1), new Ascent(1), new Descent(1))));
    }
}
