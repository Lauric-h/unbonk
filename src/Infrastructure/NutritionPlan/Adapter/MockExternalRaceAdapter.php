<?php

namespace App\Infrastructure\NutritionPlan\Adapter;

use App\Domain\NutritionPlan\DTO\ExternalAidStationDTO;
use App\Domain\NutritionPlan\DTO\ExternalEventDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;
use App\Domain\NutritionPlan\Port\ExternalRacePort;

/**
 * Mock implementation of ExternalRacePort for development/testing.
 * TODO: Replace with real implementation (e.g., LiveTrail API adapter).
 */
final class MockExternalRaceAdapter implements ExternalRacePort
{
    /**
     * @return ExternalEventDTO[]
     */
    public function listAllEvents(): array
    {
        $events = [];
        for ($i = 0; $i < 5; ++$i) {
            $events[] = $this->getEvent('id'.$i);
        }

        return $events;
    }

    public function getEvent(string $eventId): ExternalEventDTO
    {
        $aidStations = [];
        for ($i = 0; $i < 5; ++$i) {
            $aidStations[] = new ExternalAidStationDTO(
                id: 'id'.$i,
                name: 'CP'.$i,
                location: 'Arnouvaz',
                distanceFromStart: 10,
                ascentFromStart: 100,
                descentFromStart: 200,
                cutoffTime: new \DateTimeImmutable(),
                assistanceAllowed: true
            );
        }

        $races = [];
        for ($i = 0; $i < 3; ++$i) {
            $races[] = new ExternalRaceDTO(
                id: 'id'.$i,
                eventId: $eventId,
                name: 'UTMB'.$i,
                distance: 1000,
                ascent: 2000,
                descent: 2000,
                startDateTime: new \DateTimeImmutable(),
                url: null,
                startLocation: 'Chamonix',
                finishLocation: 'Chamonix',
                aidStations: $aidStations,
            );
        }

        return new ExternalEventDTO(
            id: $eventId,
            name: 'UTMB',
            location: 'Chamonix',
            date: new \DateTimeImmutable(),
            url: null,
            races: $races
        );
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function getRaceDetails(string $raceId): ?ExternalRaceDTO
    {
        $aidStations = [];
        for ($i = 0; $i < 5; ++$i) {
            $aidStations[] = new ExternalAidStationDTO(
                id: 'id'.$i,
                name: 'CP'.$i,
                location: 'Arnouvaz',
                distanceFromStart: 10,
                ascentFromStart: 100,
                descentFromStart: 200,
                cutoffTime: new \DateTimeImmutable(),
                assistanceAllowed: true
            );
        }

        return new ExternalRaceDTO(
            id: $raceId,
            eventId: 'eventid',
            name: 'CCC',
            distance: 100,
            ascent: 6000,
            descent: 6000,
            startDateTime: new \DateTimeImmutable(),
            url: null,
            startLocation: 'Courmayeur',
            finishLocation: 'Chamonix',
            aidStations: $aidStations,
        );
    }
}
