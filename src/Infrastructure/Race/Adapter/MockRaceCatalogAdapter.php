<?php

namespace App\Infrastructure\Race\Adapter;

use App\Application\Race\Exception\CatalogEventNotFoundException;
use App\Application\Race\Exception\CatalogRaceNotFoundException;
use App\Application\Race\Port\RaceCatalogPort;
use App\Application\Race\ReadModel\CatalogAidStationReadModel;
use App\Application\Race\ReadModel\CatalogEventReadModel;
use App\Application\Race\ReadModel\CatalogRaceReadModel;

/**
 * Mock implementation of RaceCatalogPort for development/testing.
 * TODO: Replace with real implementation
 */

final class MockRaceCatalogAdapter implements RaceCatalogPort
{
    /**
     * @return CatalogEventReadModel[]
     */
    public function listAllEvents(): array
    {
        return [$this->buildUtmb(), $this->buildEcotrail()];
    }

    public function getEvent(string $eventId): CatalogEventReadModel
    {
        foreach ($this->listAllEvents() as $event) {
            if ($event->id === $eventId) {
                return $event;
            }
        }

        throw new CatalogEventNotFoundException($eventId);
    }

    public function getRace(string $eventId, string $raceId): CatalogRaceReadModel
    {
        $event = $this->getEvent($eventId);

        foreach ($event->races as $race) {
            if ($race->id === $raceId) {
                return $race;
            }
        }

        throw new CatalogRaceNotFoundException($eventId, $raceId);
    }

    private function buildUtmb(): CatalogEventReadModel
    {
        $utmb = new CatalogRaceReadModel(
            id: 'race-utmb-170',
            eventId: 'event-1',
            eventName: 'Ultra-Trail du Mont-Blanc',
            name: 'UTMB',
            distanceInMeters: 170_000,
            ascent: 10_000,
            descent: 10_000,
            startDateTime: new \DateTimeImmutable('2026-08-28 17:00:00'),
            maxDurationInMinutes: 46 * 60,
            url: 'https://utmb.world/utmb',
            startLocation: 'Chamonix, France',
            finishLocation: 'Chamonix, France',
            aidStations: [
                new CatalogAidStationReadModel(
                    id: 'aid-1',
                    name: 'Les Contamines',
                    location: 'Les Contamines-Montjoie, France',
                    distanceFromStartInMeters: 31_000,
                    ascentFromStart: 1200,
                    descentFromStart: 800,
                    cutoffOffsetInMinutes: 5 * 60 + 30,
                    assistanceAllowed: true,
                ),
                new CatalogAidStationReadModel(
                    id: 'aid-2',
                    name: 'Courmayeur',
                    location: 'Courmayeur, Italie',
                    distanceFromStartInMeters: 79_000,
                    ascentFromStart: 5100,
                    descentFromStart: 4400,
                    cutoffOffsetInMinutes: 15 * 60,
                    assistanceAllowed: true,
                ),
            ],
        );

        $occ = new CatalogRaceReadModel(
            id: 'race-occ-56',
            eventId: 'event-1',
            eventName: 'Ultra-Trail du Mont-Blanc',
            name: 'OCC',
            distanceInMeters: 56_000,
            ascent: 3500,
            descent: 3500,
            startDateTime: new \DateTimeImmutable('2026-08-27 08:00:00'),
            maxDurationInMinutes: 14 * 60,
            url: null,
            startLocation: 'Orsières, Suisse',
            finishLocation: 'Chamonix, France',
            aidStations: [],
        );

        return new CatalogEventReadModel(
            id: 'event-1',
            name: 'Ultra-Trail du Mont-Blanc',
            location: 'Chamonix, France',
            date: new \DateTimeImmutable('2026-08-24'),
            url: 'https://utmb.world',
            races: [$utmb, $occ],
        );
    }

    private function buildEcotrail(): CatalogEventReadModel
    {
        $race = new CatalogRaceReadModel(
            id: 'race-eco-80',
            eventId: 'event-2',
            eventName: 'EcoTrail Paris',
            name: 'EcoTrail 80K',
            distanceInMeters: 80_000,
            ascent: 1200,
            descent: 1200,
            startDateTime: new \DateTimeImmutable('2026-03-14 06:00:00'),
            maxDurationInMinutes: 46 * 60,
            url: 'https://ecotrail-paris.com',
            startLocation: 'Château de Versailles, France',
            finishLocation: 'Paris, France',
            aidStations: [
                new CatalogAidStationReadModel(
                    id: 'aid-3',
                    name: 'Bois de Boulogne',
                    location: 'Bois de Boulogne, France',
                    distanceFromStartInMeters: 60_000,
                    ascentFromStart: 600,
                    descentFromStart: 600,
                    cutoffOffsetInMinutes: 60,
                    assistanceAllowed: false,
                ),
            ],
        );

        return new CatalogEventReadModel(
            id: 'event-2',
            name: 'EcoTrail Paris',
            location: 'Paris, France',
            date: new \DateTimeImmutable('2026-03-14'),
            url: 'https://ecotrail-paris.com',
            races: [$race],
        );
    }
}
