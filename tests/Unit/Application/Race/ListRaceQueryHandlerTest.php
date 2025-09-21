<?php

namespace App\Tests\Unit\Application\Race;

use App\Application\Race\ReadModel\ListRaceReadModel;
use App\Application\Race\ReadModel\RaceReadModel;
use App\Application\Race\UseCase\ListRace\ListRaceQuery;
use App\Application\Race\UseCase\ListRace\ListRaceQueryHandler;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Infrastructure\Race\Persistence\Repository\DoctrineRacesCatalog;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;

final class ListRaceQueryHandlerTest extends TestCase
{
    public function testListRace(): void
    {
        $date = new DatePoint('2025-03-19');
        $race = Race::create(
            'id1',
            $date,
            'Le Bélier',
            Profile::create(new Distance(42), new Ascent(2000), new Descent(2000)),
            new Address('La Clusaz', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $race2 = Race::create(
            'id2',
            $date,
            'Trail des Aravis',
            Profile::create(new Distance(50), new Ascent(3000), new Descent(3000)),
            new Address('Thônes', '74xxx'),
            'runner-id',
            'startId',
            'finishId'
        );

        $races = [$race, $race2];

        $repository = $this->createMock(DoctrineRacesCatalog::class);
        $handler = new ListRaceQueryHandler($repository);

        $repository->expects($this->once())
            ->method('getAll')
            ->with('runner-id')
            ->willReturn($races);

        $actual = ($handler)(new ListRaceQuery('runner-id'));

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
        $this->assertSame(2000, $actual->races[0]->profile->ascent);
        $this->assertSame(2000, $actual->races[0]->profile->descent);
        $this->assertSame('La Clusaz', $actual->races[0]->address->city);

        $this->assertSame($date->format('Y-m-d'), $actual->races[1]->date);
        $this->assertSame('Trail des Aravis', $actual->races[1]->name);
        $this->assertSame(50, $actual->races[1]->profile->distance);
        $this->assertSame(3000, $actual->races[1]->profile->ascent);
        $this->assertSame(3000, $actual->races[1]->profile->descent);
        $this->assertSame('Thônes', $actual->races[1]->address->city);
    }
}
