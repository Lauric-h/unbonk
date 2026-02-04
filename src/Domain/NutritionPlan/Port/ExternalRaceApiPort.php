<?php

namespace App\Domain\NutritionPlan\Port;

use App\Domain\NutritionPlan\DTO\ExternalEventDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;

interface ExternalRaceApiPort
{
    /**
     * Search events by name.
     *
     * @return ExternalEventDTO[]
     */
    public function searchEvents(string $query): array;

    /**
     * Get all races for an event.
     *
     * @return ExternalRaceDTO[]
     */
    public function getEventRaces(string $eventId): array;

    /**
     * Get a specific race with all its details (including aid stations).
     */
    public function getRaceDetails(string $raceId): ?ExternalRaceDTO;
}
