<?php

namespace App\Tests\Unit\Application\Race;

use App\Application\Race\UseCase\GetRace\GetRaceQuery;
use App\Application\Race\UseCase\GetRace\GetRaceQueryHandler;
use App\Domain\Race\Entity\Address;
use App\Domain\Race\Entity\Profile;
use App\Domain\Race\Entity\Race;
use App\Infrastructure\Race\Persistence\DoctrineRacesCatalog;
use App\UI\Http\Rest\Race\View\RaceReadModel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Clock\DatePoint;

final class GetRaceCommandHandlerTest extends TestCase
{
    public function testGetRace(): void
    {
        $repository = $this->createMock(DoctrineRacesCatalog::class);
        $query = new GetRaceQuery('id', 'runner-id');
        $handler = new GetRaceQueryHandler($repository);
        $date = new DatePoint('2025-03-19');
        $race = new Race(
            'id',
            $date,
            'Le Bélier',
            new Profile(42, 2000, 2000),
            new Address('La Clusaz', '74xxx'),
            'runner-id'
        );

        $repository->expects($this->once())
            ->method('getByIdAndRunnerId')
            ->with('id', 'runner-id')
            ->willReturn($race);

        $actual = ($handler)($query);

        $this->assertInstanceOf(RaceReadModel::class, $actual);
        $this->assertSame($race->id, $actual->id);
        $this->assertSame($race->date->format('Y-m-d'), $actual->date);
        $this->assertSame($race->name, $actual->name);
        $this->assertSame($race->profile->distance, $actual->profile->distance);
        $this->assertSame($race->profile->elevationGain, $actual->profile->elevationGain);
        $this->assertSame($race->profile->elevationLoss, $actual->profile->elevationLoss);
        $this->assertSame($race->address->city, $actual->address->city);
        $this->assertSame($race->address->postalCode, $actual->address->postalCode);
        $this->assertSame($race->runnerId, $actual->runnerId);
    }
}
