<?php

namespace App\Infrastructure\NutritionPlan\Adapter;

use App\Domain\NutritionPlan\DTO\ExternalAidStationDTO;
use App\Domain\NutritionPlan\DTO\ExternalEventDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;
use App\Domain\NutritionPlan\Port\ExternalRacePort;
use Symfony\Component\Uid\Uuid;

/**
 * Mock implementation of ExternalRacePort for development/testing.
 * Returns real CCC (Courmayeur-Champex-Chamonix) data from UTMB.
 */
final class MockExternalRaceAdapter implements ExternalRacePort
{
    private const EVENT_ID = '4ac9cf45-3864-4751-aa2c-19503c7877ac';
    private const RACE_ID = '4ac9cf45-3864-4751-aa2c-19503c7877ac';

    /**
     * @return ExternalEventDTO[]
     */
    public function listAllEvents(): array
    {
        return [$this->getEvent(self::EVENT_ID)];
    }

    public function getEvent(string $eventId): ExternalEventDTO
    {
        $aidStations = [
            new ExternalAidStationDTO(
                id: '2594c77d-7cbc-44ab-9b59-364c680af82f',
                name: 'Refuge Bertone',
                location: 'Bertone',
                distanceFromStart: 13000,
                ascentFromStart: 1433,
                descentFromStart: 681,
                cutoffTime: new \DateTimeImmutable('2026-08-28 13:45:00'),
                assistanceAllowed: true
            ),
            new ExternalAidStationDTO(
                id: '214508a8-6092-488f-96f5-70a4e3440531',
                name: 'Arnouvaz',
                location: 'Arnouvaz',
                distanceFromStart: 26000,
                ascentFromStart: 1881,
                descentFromStart: 1331,
                cutoffTime: new \DateTimeImmutable('2026-08-28 16:30:00'),
                assistanceAllowed: true
            ),
            new ExternalAidStationDTO(
                id: '1a172f95-c56d-4a88-8c42-6e13948a49d3',
                name: 'La Fouly',
                location: 'La Fouly',
                distanceFromStart: 40000,
                ascentFromStart: 2709,
                descentFromStart: 2332,
                cutoffTime: new \DateTimeImmutable('2026-08-28 20:15:00'),
                assistanceAllowed: true
            ),
            new ExternalAidStationDTO(
                id: 'a5a8c5c0-529a-4a20-a47d-116d45aeda58',
                name: 'Champex',
                location: 'Champex-Lac',
                distanceFromStart: 53000,
                ascentFromStart: 3272,
                descentFromStart: 3021,
                cutoffTime: new \DateTimeImmutable('2026-08-28 23:15:00'),
                assistanceAllowed: true
            ),
            new ExternalAidStationDTO(
                id: 'ff9ab4c5-18f1-41f2-9877-a37ea001409a',
                name: 'Trient',
                location: 'Trient',
                distanceFromStart: 71000,
                ascentFromStart: 4168,
                descentFromStart: 4082,
                cutoffTime: new \DateTimeImmutable('2026-08-29 04:00:00'),
                assistanceAllowed: true
            ),
            new ExternalAidStationDTO(
                id: '6d847f3f-3a8d-4322-a4bd-b0b7f5b55bb2',
                name: 'Vallorcine',
                location: 'Vallorcine',
                distanceFromStart: 83000,
                ascentFromStart: 5043,
                descentFromStart: 4981,
                cutoffTime: new \DateTimeImmutable('2026-08-29 07:15:00'),
                assistanceAllowed: true
            ),
            new ExternalAidStationDTO(
                id: '25437bfe-0c53-48be-bb7f-402060636015',
                name: 'La Flégère',
                location: 'Chamonix',
                distanceFromStart: 94000,
                ascentFromStart: 5998,
                descentFromStart: 5332,
                cutoffTime: new \DateTimeImmutable('2026-08-29 10:45:00'),
                assistanceAllowed: true
            ),
        ];

        $race = new ExternalRaceDTO(
            id: self::RACE_ID,
            eventId: self::EVENT_ID,
            eventName: 'UTMB',
            name: 'CCC',
            distance: 101000,
            ascent: 6000,
            descent: 6000,
            startDateTime: new \DateTimeImmutable('2026-08-28 09:00:52'),
            url: null,
            startLocation: 'Courmayeur',
            finishLocation: 'Chamonix',
            aidStations: $aidStations,
        );

        return new ExternalEventDTO(
            id: self::EVENT_ID,
            name: 'UTMB',
            location: 'Chamonix',
            date: new \DateTimeImmutable('2026-08-28'),
            url: null,
            races: [$race]
        );
    }

    public function getRaceDetails(string $eventId, string $raceId): ?ExternalRaceDTO
    {
        $event = $this->getEvent($eventId);
        
        foreach ($event->races as $race) {
            if ($race->id === $raceId) {
                return $race;
            }
        }

        return null;
    }
}
