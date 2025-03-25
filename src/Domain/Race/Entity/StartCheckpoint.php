<?php

namespace App\Domain\Race\Entity;

final class StartCheckpoint extends Checkpoint
{
    public function __construct(
        string $id,
        string $name,
        string $location,
        Race $race,
    ) {
        $metricsFromStart = new MetricsFromStart(0, 0, 0, 0);
        parent::__construct(
            $id,
            $name,
            $location,
            $metricsFromStart,
            $race
        );
    }

    public function update(string $name, string $location): void
    {
        $this->setName($name);
        $this->setLocation($location);
    }

    public function validate(): void
    {
        if (0 !== $this->getMetricsFromStart()->distance
            || 0 !== $this->getMetricsFromStart()->elevationGain
            || 0 !== $this->getMetricsFromStart()->elevationLoss
            || 0 !== $this->getMetricsFromStart()->estimatedTimeInMinutes
        ) {
            throw new \DomainException('Invalid Start Checkpoint Metrics');
        }

        if (CheckpointType::Start !== $this->getCheckpointType()) {
            throw new \DomainException('Invalid Checkpoint Type');
        }
    }

    public function getCheckpointType(): CheckpointType
    {
        return CheckpointType::Start;
    }
}
