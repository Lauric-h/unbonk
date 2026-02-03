<?php

namespace App\Infrastructure\NutritionPlan\Adapter;

use App\Domain\NutritionPlan\DTO\ExternalEventDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;
use App\Domain\NutritionPlan\Port\ExternalRaceApiPort;

/**
 * Mock implementation of ExternalRaceApiPort for development/testing.
 * TODO: Replace with real implementation (e.g., LiveTrail API adapter).
 */
final class MockExternalRaceApiAdapter implements ExternalRaceApiPort
{
    /**
     * @return ExternalEventDTO[]
     */
    public function searchEvents(string $query): array
    {
        // Mock implementation - returns empty array
        return [];
    }

    /**
     * @return ExternalRaceDTO[]
     */
    public function getEventRaces(string $eventId): array
    {
        // Mock implementation - returns empty array
        return [];
    }

    public function getRaceDetails(string $raceId): ?ExternalRaceDTO
    {
        // Mock implementation - returns null
        return null;
    }
}
