<?php

namespace App\Domain\Race\Entity;

final class AidStationCheckpoint extends AbstractCheckpoint
{
    public function __construct(
        string $id,
        string $name,
        string $location,
        MetricsFromStart $metricsFromStart,
        Race $race,
    ) {
        parent::__construct(
            $id,
            $name,
            $location,
            CheckpointType::AidStation,
            $metricsFromStart,
            $race,
        );
    }

    public function validate(): void
    {
        if (CheckpointType::AidStation !== $this->getCheckpointType()) {
            throw new \DomainException('Invalid Checkpoint Type');
        }
    }
}
