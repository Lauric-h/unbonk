<?php

namespace App\Application\Race\UseCase\ImportRace;

use App\Domain\Shared\Bus\CommandInterface;

final readonly class ImportRaceCommand implements CommandInterface
{
    public function __construct(
        public string $runnerId,
        public string $eventId,
        public string $raceId,
    ) {
    }
}