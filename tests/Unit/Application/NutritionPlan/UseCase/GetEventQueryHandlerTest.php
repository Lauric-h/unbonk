<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\ReadModel\External\ExternalEventReadModel;
use App\Application\NutritionPlan\UseCase\GetEvent\GetEventQuery;
use App\Application\NutritionPlan\UseCase\GetEvent\GetEventQueryHandler;
use App\Domain\NutritionPlan\DTO\ExternalEventDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;
use App\Domain\NutritionPlan\Port\ExternalRacePort;
use PHPUnit\Framework\TestCase;

final class GetEventQueryHandlerTest extends TestCase
{
    public function testGetEventReturnsReadModel(): void
    {
        $racePort = $this->createMock(ExternalRacePort::class);
        $handler = new GetEventQueryHandler($racePort);

        $eventDTO = $this->createEventDTO();

        $racePort->expects($this->once())
            ->method('getEvent')
            ->with('event-1')
            ->willReturn($eventDTO);

        $result = ($handler)(new GetEventQuery('event-1'));

        $this->assertInstanceOf(ExternalEventReadModel::class, $result);
        $this->assertSame('event-1', $result->id);
        $this->assertSame('UTMB Mont-Blanc', $result->name);
        $this->assertSame('Chamonix, France', $result->location);
        $this->assertSame('https://utmb.world', $result->url);
        $this->assertCount(1, $result->races);
    }

    private function createEventDTO(): ExternalEventDTO
    {
        $race = new ExternalRaceDTO(
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

        return new ExternalEventDTO(
            id: 'event-1',
            name: 'UTMB Mont-Blanc',
            location: 'Chamonix, France',
            date: new \DateTimeImmutable('2024-08-30'),
            url: 'https://utmb.world',
            races: [$race],
        );
    }
}
