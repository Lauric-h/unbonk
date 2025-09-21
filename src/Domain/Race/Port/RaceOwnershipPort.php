<?php

namespace App\Domain\Race\Port;

interface RaceOwnershipPort
{
    public function userOwnsRace(string $raceId, string $runnerId): bool;
}
