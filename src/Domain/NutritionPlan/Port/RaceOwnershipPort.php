<?php

namespace App\Domain\NutritionPlan\Port;

interface RaceOwnershipPort
{
    public function userOwnsRace(string $raceId, string $runnerId): bool;
}
