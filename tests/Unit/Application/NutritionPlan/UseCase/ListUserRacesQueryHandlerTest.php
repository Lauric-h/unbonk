<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\ReadModel\ImportedRaceReadModel;
use App\Application\NutritionPlan\UseCase\ListUserRaces\ListUserRacesQuery;
use App\Application\NutritionPlan\UseCase\ListUserRaces\ListUserRacesQueryHandler;
use App\Domain\NutritionPlan\Entity\ImportedRace;
use App\Domain\NutritionPlan\Repository\RacesCatalog;
use PHPUnit\Framework\TestCase;

final class ListUserRacesQueryHandlerTest extends TestCase
{
    public function testListUserRacesReturnsReadModels(): void
    {
        $racesCatalog = $this->createMock(RacesCatalog::class);
        $handler = new ListUserRacesQueryHandler($racesCatalog);

        $userId = 'user-123';
        $races = $this->createImportedRaces($userId);

        $racesCatalog->expects($this->once())
            ->method('findByRunnerId')
            ->with($userId)
            ->willReturn($races);

        $result = ($handler)(new ListUserRacesQuery($userId));

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(ImportedRaceReadModel::class, $result);

        $this->assertSame('race-1', $result[0]->id);
        $this->assertSame('UTMB 2024', $result[0]->name);
        $this->assertSame(171000, $result[0]->distance);

        $this->assertSame('race-2', $result[1]->id);
        $this->assertSame('Tor des Géants 2024', $result[1]->name);
        $this->assertSame(330000, $result[1]->distance);
    }

    public function testListUserRacesReturnsEmptyArrayWhenNoRaces(): void
    {
        $racesCatalog = $this->createMock(RacesCatalog::class);
        $handler = new ListUserRacesQueryHandler($racesCatalog);

        $userId = 'user-123';

        $racesCatalog->expects($this->once())
            ->method('findByRunnerId')
            ->with($userId)
            ->willReturn([]);

        $result = ($handler)(new ListUserRacesQuery($userId));

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @return ImportedRace[]
     */
    private function createImportedRaces(string $runnerId): array
    {
        $race1 = new ImportedRace(
            id: 'race-1',
            runnerId: $runnerId,
            externalRaceId: 'ext-race-1',
            externalEventId: 'ext-event-1',
            name: 'UTMB 2024',
            distance: 171000,
            ascent: 10000,
            descent: 10000,
            startDateTime: new \DateTimeImmutable('2024-08-30 18:00:00'),
            location: 'Chamonix',
        );

        $race2 = new ImportedRace(
            id: 'race-2',
            runnerId: $runnerId,
            externalRaceId: 'ext-race-2',
            externalEventId: 'ext-event-2',
            name: 'Tor des Géants 2024',
            distance: 330000,
            ascent: 24000,
            descent: 24000,
            startDateTime: new \DateTimeImmutable('2024-09-08 10:00:00'),
            location: 'Courmayeur',
        );

        return [$race1, $race2];
    }
}
