<?php

namespace App\Infrastructure\Race\Service;

use App\Application\Race\Service\RaceAccessPort;
use App\Domain\Race\Exception\RaceNotFoundException;
use App\Domain\Race\Repository\RacesCatalog;

final readonly class RaceAccessAdapter implements RaceAccessPort
{
    public function __construct(private RacesCatalog $racesCatalog)
    {
    }

    public function checkAccess(string $raceId, string $runnerId): bool
    {
        try {
            $this->racesCatalog->getByIdAndRunnerId($raceId, $runnerId);

            return true;
        } catch (RaceNotFoundException $exception) {
            return false;
        }
    }
}
