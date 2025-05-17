<?php

namespace App\Infrastructure\NutritionPlan\Adapter;

use App\Application\Race\Service\RaceAccessPort;
use App\Domain\NutritionPlan\Port\RaceOwnershipPort;

final readonly class RaceOwnershipAdapter implements RaceOwnershipPort
{
    public function __construct(private RaceAccessPort $raceAccessPort)
    {
    }

    public function userOwnsRace(string $raceId, string $runnerId): bool
    {
        return $this->raceAccessPort->checkAccess($raceId, $runnerId);
    }
}
