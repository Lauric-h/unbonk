<?php

namespace App\Infrastructure\NutritionPlan\Adapter;

use App\Domain\NutritionPlan\DTO\ExternalAidStationDTO;
use App\Domain\NutritionPlan\DTO\ExternalEventDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;
use App\Domain\NutritionPlan\Port\ExternalRacePort;
use Symfony\Component\Uid\Uuid;

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
            $events[] = $this->getEvent(Uuid::v4()->toRfc4122());
        }

        return $events;
    }

    public function getEvent(string $eventId): ExternalEventDTO
    {
        $aidStations = [];
        for ($i = 0; $i < 5; ++$i) {
            $aidStations[] = new ExternalAidStationDTO(
                id: Uuid::v4()->toRfc4122(),
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
                id: Uuid::v4()->toRfc4122(),
                eventId: $eventId,
                eventName: 'UTMB Event',
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
    public function getRaceDetails(string $eventId, string $raceId): ?ExternalRaceDTO
    {
        $aidStations = [];
        for ($i = 0; $i < 5; ++$i) {
            $aidStations[] = new ExternalAidStationDTO(
                id: Uuid::v4()->toRfc4122(),
                name: 'CP'.$i,
                location: 'Col du Granon',
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
            eventName: 'Serre Chevalier Event',
            name: 'Serre Che Trail',
            distance: 60,
            ascent: 4000,
            descent: 4000,
            startDateTime: new \DateTimeImmutable(),
            url: null,
            startLocation: 'Serre Chevalier',
            finishLocation: 'Serre Chevalier',
            aidStations: $aidStations,
        );
    }
}
