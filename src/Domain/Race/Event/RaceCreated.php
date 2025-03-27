<?php

namespace App\Domain\Race\Event;

use App\Domain\Shared\Event\DomainEvent;

final readonly class RaceCreated implements DomainEvent
{
    public function __construct(
        public string $id,
        public string $runnerId,
    ) {
    }
}
