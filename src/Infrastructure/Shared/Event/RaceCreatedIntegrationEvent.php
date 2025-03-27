<?php

namespace App\Infrastructure\Shared\Event;

use App\Domain\Shared\Event\IntegrationEvent;

final class RaceCreatedIntegrationEvent implements IntegrationEvent
{
    public function __construct(
        public string $id,
        public string $runnerId,
    ) {
    }
}
