<?php

namespace App\Application\Race\Service;

interface RaceAccessPort
{
    public function checkAccess(string $raceId, string $runnerId): bool;
}
