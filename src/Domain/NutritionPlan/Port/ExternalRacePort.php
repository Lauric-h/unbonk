<?php

namespace App\Domain\NutritionPlan\Port;

use App\Domain\NutritionPlan\DTO\ExternalEventDTO;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;

interface ExternalRacePort
{
    /**
     * @return ExternalEventDTO[]
     */
    public function listAllEvents(): array;

    public function getEvent(string $eventId): ExternalEventDTO;

    public function getRaceDetails(string $eventId, string $raceId): ?ExternalRaceDTO;
}
