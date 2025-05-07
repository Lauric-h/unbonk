<?php

namespace App\Domain\Race\Event;

use App\Domain\Shared\Event\DomainEvent;

final readonly class RaceCheckpointsChanged implements DomainEvent
{
    public function __construct(public string $raceId, public string $runnerId)
    {
    }
}
