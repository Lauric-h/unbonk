<?php

namespace App\Tests\Unit\UI\Race;

use App\Application\Race\ReadModel\ListRaceReadModel;
use App\Application\Race\ReadModel\RaceReadModel;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use PHPUnit\Framework\TestCase;

final class ListRaceReadModelTest extends TestCase
{
    public function testFromRaces(): void
    {
        $date = new \DateTimeImmutable('2025-01-01');
        $race = Race::create(
            'id1',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $date = new \DateTimeImmutable('2025-01-01');
        $race2 = Race::create(
            'id2',
            $date,
            'Trail des Aravis',
            new Profile(50, 3000, 3000),
            new Address('Thônes', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $races = [$race, $race2];

        $actual = ListRaceReadModel::fromRaces($races);

        $this->assertInstanceOf(ListRaceReadModel::class, $actual);
        foreach ($actual->races as $r) {
            $this->assertInstanceOf(RaceReadModel::class, $r);
            $this->assertSame('runner-id', $r->runnerId);
            $this->assertSame($date->format('Y-m-d'), $r->date);
            $this->assertSame('74xxx', $r->address->postalCode);
        }
        $this->assertSame('id1', $actual->races[0]->id);
        $this->assertSame('Le Bélier', $actual->races[0]->name);
        $this->assertSame(42, $actual->races[0]->profile->distance);
        $this->assertSame(2000, $actual->races[0]->profile->elevationGain);
        $this->assertSame(2000, $actual->races[0]->profile->elevationLoss);
        $this->assertSame('La Clusaz', $actual->races[0]->address->city);

        $this->assertSame($date->format('Y-m-d'), $actual->races[1]->date);
        $this->assertSame('Trail des Aravis', $actual->races[1]->name);
        $this->assertSame(50, $actual->races[1]->profile->distance);
        $this->assertSame(3000, $actual->races[1]->profile->elevationGain);
        $this->assertSame(3000, $actual->races[1]->profile->elevationLoss);
        $this->assertSame('Thônes', $actual->races[1]->address->city);
    }
}
