<?php

namespace App\Tests\Unit\UI\Race;

use App\Application\Race\ReadModel\AddressReadModel;
use App\Application\Race\ReadModel\CheckpointReadModel;
use App\Application\Race\ReadModel\ProfileReadModel;
use App\Application\Race\ReadModel\RaceReadModel;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use PHPUnit\Framework\TestCase;

final class RaceReadModelTest extends TestCase
{
    public function testFromRace(): void
    {
        $date = new \DateTimeImmutable('2025-01-01');
        $race = Race::create(
            'raceId',
            $date,
            'Le Bélier',
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );
        $expected = new RaceReadModel(
            'raceId',
            $date->format('Y-m-d'),
            'Le Bélier',
            new ProfileReadModel(42, 2000, 2000),
            new AddressReadModel('La Clusaz', '74xxx'),
            'runner-id'
        );

        $actual = RaceReadModel::fromRace($race);

        $this->assertSame($expected->id, $actual->id);
        $this->assertSame($expected->name, $actual->name);
        $this->assertSame($expected->date, $actual->date);
        $this->assertEquals($expected->profile, $actual->profile);
        $this->assertEquals($expected->address, $actual->address);
        $this->assertSame($expected->runnerId, $actual->runnerId);
    }

    public function testFromRaceWithCheckpoints(): void
    {
        $date = new \DateTimeImmutable('2025-01-01');
        $race = Race::create(
            'raceId',
            $date,
            'Le Bélier',
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );
        $expected = new RaceReadModel(
            'raceId',
            $date->format('Y-m-d'),
            'Le Bélier',
            new ProfileReadModel(42, 2000, 2000),
            new AddressReadModel('La Clusaz', '74xxx'),
            'runner-id'
        );

        $actual = RaceReadModel::fromRace($race);

        $this->assertCount(2, $actual->checkpoints);
        $this->assertContainsOnlyInstancesOf(CheckpointReadModel::class, $actual->checkpoints);
    }
}
