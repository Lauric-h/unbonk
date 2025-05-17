<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\FinishCheckpoint;
use App\Domain\Race\Entity\IntermediateCheckpoint;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Domain\Race\Entity\StartCheckpoint;
use App\Domain\Race\Exception\CheckpointWithSameDistanceException;
use PHPUnit\Framework\TestCase;

final class RaceTest extends TestCase
{
    public function testCreateAddsStartAndFinishCheckpoints(): void
    {
        $race = Race::create(
            'raceId',
            new \DateTimeImmutable(),
            'Le Bélier',
            Profile::create(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $this->assertCount(2, $race->getCheckpoints());
        $this->assertInstanceOf(StartCheckpoint::class, $race->getCheckpoints()[0]);
        $this->assertInstanceOf(FinishCheckpoint::class, $race->getCheckpoints()[1]);
        $this->assertSame(0, $race->getCheckpoints()[0]->getMetricsFromStart()->distance->value);
        $this->assertSame(0, $race->getCheckpoints()[0]->getMetricsFromStart()->ascent->value);
        $this->assertSame(0, $race->getCheckpoints()[0]->getMetricsFromStart()->descent->value);
        $this->assertSame(0, $race->getCheckpoints()[0]->getMetricsFromStart()->estimatedTimeInMinutes->minutes);

        $this->assertSame($race->profile->distance->value, $race->getCheckpoints()[1]->getMetricsFromStart()->distance->value);
        $this->assertSame($race->profile->ascent->value, $race->getCheckpoints()[1]->getMetricsFromStart()->ascent->value);
        $this->assertSame($race->profile->descent->value, $race->getCheckpoints()[1]->getMetricsFromStart()->descent->value);
        $this->assertSame(360, $race->getCheckpoints()[1]->getMetricsFromStart()->estimatedTimeInMinutes->minutes);
    }

    public function testGetStartCheckpoint(): void
    {
        $race = Race::create(
            'raceId',
            new \DateTimeImmutable(),
            'Le Bélier',
            Profile::create(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $this->assertInstanceOf(StartCheckpoint::class, $race->getStartCheckpoint());
        $this->assertSame(0, $race->getStartCheckpoint()->getMetricsFromStart()->distance->value);
        $this->assertSame(0, $race->getStartCheckpoint()->getMetricsFromStart()->ascent->value);
        $this->assertSame(0, $race->getStartCheckpoint()->getMetricsFromStart()->descent->value);
    }

    public function testGetFinishCheckpoint(): void
    {
        $race = Race::create(
            'raceId',
            new \DateTimeImmutable(),
            'Le Bélier',
            Profile::create(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $this->assertInstanceOf(FinishCheckpoint::class, $race->getFinishCheckpoint());
        $this->assertSame($race->profile->distance, $race->getFinishCheckpoint()->getMetricsFromStart()->distance);
        $this->assertSame($race->profile->ascent, $race->getFinishCheckpoint()->getMetricsFromStart()->ascent);
        $this->assertSame($race->profile->descent, $race->getFinishCheckpoint()->getMetricsFromStart()->descent);
    }

    public function testUpdate(): void
    {
        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            Profile::create(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $race->update(
            'Le Bélier update',
            new \DateTimeImmutable('2025-01-02'),
            43,
            2001,
            2001,
            'La Clusaze',
            '74xx1'
        );

        $this->assertSame('raceId', $race->id);
        $this->assertSame('2025-01-02', $race->date->format('Y-m-d'));
        $this->assertSame(43, $race->profile->distance->value);
        $this->assertSame(2001, $race->profile->ascent->value);
        $this->assertSame(2001, $race->profile->descent->value);
        $this->assertSame('La Clusaze', $race->address->city);
        $this->assertSame('74xx1', $race->address->postalCode);
        $this->assertSame(43, $race->getFinishCheckpoint()->getMetricsFromStart()->distance->value);
        $this->assertSame(2001, $race->getFinishCheckpoint()->getMetricsFromStart()->ascent->value);
        $this->assertSame(2001, $race->getFinishCheckpoint()->getMetricsFromStart()->descent->value);
    }

    public function testAddCheckpoint(): void
    {
        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            Profile::create(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $checkpoint = new IntermediateCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(120, 10, 1000, 1000),
            $race
        );

        $race->addCheckpoint($checkpoint);

        $this->assertCount(3, $race->getCheckpoints());
        $this->assertSame($checkpoint, $race->getCheckpoints()->get(1));
    }

    public function testAddCheckpointToSameDistanceThrowsException(): void
    {
        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            Profile::create(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $checkpoint = new IntermediateCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(120, 10, 1000, 1000),
            $race
        );

        $checkpoint2 = new IntermediateCheckpoint(
            'cpId2',
            'name2',
            'location2',
            MetricsFromStart::create(120, 10, 1000, 1000),
            $race
        );

        $this->expectException(CheckpointWithSameDistanceException::class);
        $this->expectExceptionMessage('Checkpoint already exists at distance 10');

        $race->addCheckpoint($checkpoint);
        $race->addCheckpoint($checkpoint2);
    }

    public function testGetCheckpointAtDistance(): void
    {
        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            Profile::create(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $checkpoint = new IntermediateCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(120, 10, 1000, 1000),
            $race
        );

        $race->addCheckpoint($checkpoint);

        $actual = $race->getCheckpointAtDistance(10);

        $this->assertSame($checkpoint, $actual);
    }

    public function testSortCheckpointByDistance()
    {
        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            Profile::create(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $checkpoint = new IntermediateCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(120, 10, 1000, 1000),
            $race
        );

        $race->addCheckpoint($checkpoint);

        $race->sortCheckpointByDistance();

        $this->assertSame(0, $race->getCheckpoints()->get(0)->getMetricsFromStart()->distance->value);
        $this->assertSame(10, $race->getCheckpoints()->get(1)->getMetricsFromStart()->distance->value);
        $this->assertSame(42, $race->getCheckpoints()->get(2)->getMetricsFromStart()->distance->value);
    }

    public function testRemoveCheckpointStartThrowsException(): void
    {
        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            Profile::create(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $cp = $race->getStartCheckpoint();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot remove Start or Finish Checkpoint');

        $race->removeCheckpoint($cp);
    }

    public function testRemoveCheckpointFinishThrowsException(): void
    {
        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            Profile::create(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $cp = $race->getFinishCheckpoint();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot remove Start or Finish Checkpoint');

        $race->removeCheckpoint($cp);
    }

    public function testRemoveCheckpoint(): void
    {
        $race = Race::create(
            'raceId',
            new \DateTimeImmutable('2025-01-01'),
            'Le Bélier',
            Profile::create(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $checkpoint = new IntermediateCheckpoint(
            'cpId',
            'name',
            'location',
            MetricsFromStart::create(120, 10, 1000, 1000),
            $race
        );

        $race->addCheckpoint($checkpoint);

        $race->removeCheckpoint($checkpoint);

        $this->assertCount(2, $race->getCheckpoints());
        $this->assertFalse($race->getCheckpoints()->contains($checkpoint));
    }
}
