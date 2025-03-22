<?php

namespace App\Tests\Unit\Domain\Race;

use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Checkpoint;
use App\Domain\Race\Entity\CheckpointType;
use App\Domain\Race\Entity\MetricsFromStart;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
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

        $expected = new Race(
            'id',
            $date,
            'Le Bélier update',
            new Profile(43, 2001, 2001),
            new Address('La Clusaze', '74xx1'),
            'runner-id'
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
}
