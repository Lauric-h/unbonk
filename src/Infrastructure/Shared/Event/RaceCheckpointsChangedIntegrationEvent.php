<?php

namespace App\Infrastructure\Shared\Event;

use App\Domain\Shared\Event\IntegrationEvent;
use App\Infrastructure\Race\DTO\CheckpointDTO;

final readonly class RaceCheckpointsChangedIntegrationEvent implements IntegrationEvent
{
    /**
     * @param CheckpointDTO[] $checkpoints
     */
    public function __construct(public string $raceId, public array $checkpoints)
    {
    }
}
