<?php

namespace App\Domain\Race\Event;

use App\Domain\Shared\Event\DomainEvent;

final readonly class RaceDeleted implements DomainEvent
{
    public function __construct(public string $raceId)
    {
    }
}
