<?php

namespace App\Infrastructure\Shared\Event;

use App\Domain\Shared\Event\IntegrationEvent;

final readonly class CheckpointUpdatedIntegrationEvent implements IntegrationEvent
{
    public function __construct(public string $raceId, public array $checkpoints)
    {
    }
}