<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\ReadModel\External\ExternalEventReadModel;
use App\Application\NutritionPlan\UseCase\ListAllEvents\ListAllEventsQuery;
use App\Application\NutritionPlan\UseCase\ListAllEvents\ListAllEventsQueryHandler;
use App\Domain\NutritionPlan\DTO\ExternalEventDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;
use App\Domain\NutritionPlan\Port\ExternalRacePort;
use PHPUnit\Framework\TestCase;

final class ListAllEventsQueryHandlerTest extends TestCase
{
    public function testListAllEventsReturnsReadModels(): void
    {
        $racePort = $this->createMock(ExternalRacePort::class);
        $handler = new ListAllEventsQueryHandler($racePort);

        $eventDTOs = $this->createEventDTOs();

        $racePort->expects($this->once())
            ->method('listAllEvents')
            ->willReturn($eventDTOs);

        $result = ($handler)(new ListAllEventsQuery());

        $this->assertCount(2, $result);
        $this->assertContainsOnlyInstancesOf(ExternalEventReadModel::class, $result);

        $this->assertSame('event-1', $result[0]->id);
        $this->assertSame('UTMB Mont-Blanc', $result[0]->name);
        $this->assertCount(1, $result[0]->races);

        $this->assertSame('event-2', $result[1]->id);
        $this->assertSame('Tor des Géants', $result[1]->name);
    }

    public function testListAllEventsReturnsEmptyArrayWhenNoEvents(): void
    {
        $racePort = $this->createMock(ExternalRacePort::class);
        $handler = new ListAllEventsQueryHandler($racePort);

        $racePort->expects($this->once())
            ->method('listAllEvents')
            ->willReturn([]);

        $result = ($handler)(new ListAllEventsQuery());

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @return ExternalEventDTO[]
     */
    private function createEventDTOs(): array
    {
        $race1 = new ExternalRaceDTO(
            id: 'race-1',
            eventId: 'event-1',
            name: 'UTMB 100',
            distance: 171000,
            ascent: 10000,
            descent: 10000,
            startDateTime: new \DateTimeImmutable('2024-08-30 18:00:00'),
            url: 'https://utmb.world',
            startLocation: 'Chamonix',
            finishLocation: 'Chamonix',
            aidStations: [],
        );

        $event1 = new ExternalEventDTO(
            id: 'event-1',
            name: 'UTMB Mont-Blanc',
            location: 'Chamonix, France',
            date: new \DateTimeImmutable('2024-08-30'),
            url: 'https://utmb.world',
            races: [$race1],
        );

        $event2 = new ExternalEventDTO(
            id: 'event-2',
            name: 'Tor des Géants',
            location: 'Courmayeur, Italy',
            date: new \DateTimeImmutable('2024-09-08'),
            url: 'https://tordesgeants.it',
            races: [],
        );

        return [$event1, $event2];
    }
}
