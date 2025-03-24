<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Checkpoint;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Domain\Race\Exception\CheckpointWithSameDistanceException;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;

final class RaceTest extends TestCase
{
    public function testGetStartCheckpoint(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id'
        );

        $checkpoint1 = new Checkpoint(
            'id',
            'name',
            'location',
            CheckpointType::Start,
            new MetricsFromStart(0, 0, 0, 0),
            $race
        );

        $checkpoint2 = new Checkpoint(
            'id2',
            'name2',
            'location2',
            CheckpointType::Finish,
            new MetricsFromStart(500, 42, 2000, 2000),
            $race
        );

        $race->checkpoints->add($checkpoint1);
        $race->checkpoints->add($checkpoint2);

        $actual = $race->getStartCheckpoint();

        $this->assertSame($checkpoint1, $actual);
    }

    public function testGetFinishCheckpoint(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id'
        );

        $checkpoint1 = new Checkpoint(
            'id',
            'name',
            'location',
            CheckpointType::Start,
            new MetricsFromStart(0, 0, 0, 0),
            $race
        );

        $checkpoint2 = new Checkpoint(
            'id2',
            'name2',
            'location2',
            CheckpointType::Finish,
            new MetricsFromStart(500, 42, 2000, 2000),
            $race
        );

        $race->checkpoints->add($checkpoint1);
        $race->checkpoints->add($checkpoint2);

        $actual = $race->getFinishCheckpoint();

        $this->assertSame($checkpoint2, $actual);
    }

    public function testGetFinishCheckpointWrongTypeThrowsException(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id'
        );

        $checkpoint1 = new Checkpoint(
            'id',
            'name',
            'location',
            CheckpointType::Start,
            new MetricsFromStart(0, 0, 0, 0),
            $race
        );

        $race->checkpoints->add($checkpoint1);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Invalid Checkpoint type');

        $race->getFinishCheckpoint();
    }

    public function testGetStartCheckpointWrongTypeThrowsException(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id'
        );

        $checkpoint1 = new Checkpoint(
            'id',
            'name',
            'location',
            CheckpointType::Finish,
            new MetricsFromStart(0, 0, 0, 0),
            $race
        );

        $race->checkpoints->add($checkpoint1);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Invalid Checkpoint type');

        $race->getStartCheckpoint();
    }

    public function testGetStartCheckpointEmptyThrowsException(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id'
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Race does not have start checkpoint');

        $race->getStartCheckpoint();
    }

    public function testGetFinishCheckpointEmptyThrowsException(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id'
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Race does not have finish checkpoint');

        $race->getFinishCheckpoint();
    }

    public function testUpdate(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id'
        );

        $checkpoint2 = new Checkpoint(
            'id2',
            'name2',
            'location2',
            CheckpointType::Finish,
            new MetricsFromStart(500, 42, 2000, 2000),
            $race
        );

        $race->checkpoints->add($checkpoint2);

        $expected = new Race(
            'id',
            $date,
            'Le Bélier update',
            new Profile(43, 2001, 2001),
            new Address('La Clusaze', '74xx1'),
            'runner-id',
            new ArrayCollection([$checkpoint2])
        );

        $race->update(
            'Le Bélier update',
            $date,
            43,
            2001,
            2001,
            'La Clusaze',
            '74xx1'
        );

        $this->assertEquals($expected, $race);
    }

    public function testAddCheckpoint(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id'
        );

        $checkpoint2 = new Checkpoint(
            'id2',
            'name2',
            'location2',
            CheckpointType::Finish,
            new MetricsFromStart(500, 42, 2000, 2000),
            $race
        );

        $race->addCheckpoint($checkpoint2);

        $this->assertCount(1, $race->checkpoints);
        $this->assertSame($checkpoint2, $race->checkpoints->first());
    }

    public function testAddCheckpointToSameDistanceThrowsException(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id'
        );

        $checkpoint2 = new Checkpoint(
            'id2',
            'name2',
            'location2',
            CheckpointType::Finish,
            new MetricsFromStart(500, 42, 2000, 2000),
            $race
        );
        $race->addCheckpoint($checkpoint2);

        $this->expectException(CheckpointWithSameDistanceException::class);
        $this->expectExceptionMessage('Checkpoint already exists at distance 42');
        $race->addCheckpoint($checkpoint2);
    }

    public function testGetCheckpointAtDistanceWithSameDistanceThrowsException(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
        );

        $checkpoint1 = new Checkpoint(
            'id2',
            'name2',
            'location2',
            CheckpointType::Finish,
            new MetricsFromStart(500, 42, 2000, 2000),
            $race
        );

        $checkpoint2 = new Checkpoint(
            'id2',
            'name2',
            'location2',
            CheckpointType::Finish,
            new MetricsFromStart(500, 42, 2000, 2000),
            $race
        );

        $race->checkpoints->add($checkpoint1);
        $race->checkpoints->add($checkpoint2);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Multiple checkpoint for same distance: 42');
        $race->getCheckpointAtDistance(42);
    }

    public function testGetCheckpointAtDistanceWithNoCheckpointReturnsNull(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
        );

        $checkpoint1 = new Checkpoint(
            'id2',
            'name2',
            'location2',
            CheckpointType::Finish,
            new MetricsFromStart(500, 42, 2000, 2000),
            $race
        );

        $race->checkpoints->add($checkpoint1);

        $actual = $race->getCheckpointAtDistance(1);

        $this->assertNotInstanceOf(Checkpoint::class, $actual);
    }

    public function testGetCheckpointAtDistanceWithEmptyCheckpointsReturnsNull(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
        );

        $actual = $race->getCheckpointAtDistance(1);

        $this->assertNotInstanceOf(Checkpoint::class, $actual);
    }

    public function testGetCheckpointAtDistance(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
        );

        $checkpoint1 = new Checkpoint(
            'id2',
            'name2',
            'location2',
            CheckpointType::Finish,
            new MetricsFromStart(500, 42, 2000, 2000),
            $race
        );

        $race->checkpoints->add($checkpoint1);

        $actual = $race->getCheckpointAtDistance(42);

        $this->assertSame($checkpoint1, $actual);
    }

    public function testSortCheckpointByDistance()
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
        );

        $checkpoint1 = new Checkpoint(
            'id1',
            'name1',
            'location1',
            CheckpointType::Start,
            new MetricsFromStart(1000, 42, 2000, 2000),
            $race
        );

        $checkpoint2 = new Checkpoint(
            'id2',
            'name2',
            'location2',
            CheckpointType::Finish,
            new MetricsFromStart(500, 22, 2000, 2000),
            $race
        );

        $checkpoint3 = new Checkpoint(
            'id3',
            'name3',
            'location3',
            CheckpointType::None,
            new MetricsFromStart(1500, 12, 2000, 2000),
            $race
        );

        $race->checkpoints = new ArrayCollection([$checkpoint1, $checkpoint2, $checkpoint3]);

        $race->sortCheckpointByDistance();

        $sortedCheckpoints = $race->checkpoints->toArray();
        $this->assertSame([$checkpoint3, $checkpoint2, $checkpoint1], $sortedCheckpoints);
    }

    public function testRemoveCheckpointStartThrowsException(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
        );

        $checkpoint = new Checkpoint(
            'id',
            'name',
            'location',
            CheckpointType::Start,
            new MetricsFromStart(0, 0, 0, 0),
            $race
        );

        $race->checkpoints->add($checkpoint);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot remove start or finish checkpoint');

        $race->removeCheckpoint($checkpoint);
    }

    public function testRemoveCheckpointFinishThrowsException(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
        );

        $checkpoint = new Checkpoint(
            'id',
            'name',
            'location',
            CheckpointType::Finish,
            new MetricsFromStart(0, 42, 2000, 2000),
            $race
        );

        $race->checkpoints->add($checkpoint);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot remove start or finish checkpoint');

        $race->removeCheckpoint($checkpoint);
    }

    public function testRemoveCheckpoint(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
        );

        $checkpoint1 = new Checkpoint(
            'id1',
            'name1',
            'location1',
            CheckpointType::Start,
            new MetricsFromStart(0, 0, 0, 0),
            $race
        );

        $checkpoint2 = new Checkpoint(
            'id2',
            'name2',
            'location2',
            CheckpointType::None,
            new MetricsFromStart(500, 12, 2000, 2000),
            $race
        );

        $checkpoint3 = new Checkpoint(
            'id3',
            'name3',
            'location3',
            CheckpointType::Finish,
            new MetricsFromStart(500, 42, 2000, 2000),
            $race
        );

        $race->checkpoints->add($checkpoint1);
        $race->checkpoints->add($checkpoint2);
        $race->checkpoints->add($checkpoint3);

        $race->removeCheckpoint($checkpoint2);

        $this->assertCount(2, $race->checkpoints);
        $this->assertFalse($race->checkpoints->contains($checkpoint2));
    }
}
