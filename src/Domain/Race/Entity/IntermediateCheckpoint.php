<?php

namespace App\Domain\Race\Entity;

final class IntermediateCheckpoint extends AbstractCheckpoint
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
            CheckpointType::Intermediate,
            $metricsFromStart,
            $race,
        );
    }

    public function validate(): void
    {
        if (CheckpointType::Intermediate !== $this->getCheckpointType()) {
            throw new \DomainException('Invalid Checkpoint Type');
        }
    }
}
