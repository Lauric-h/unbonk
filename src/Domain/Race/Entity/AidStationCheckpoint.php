<?php

namespace App\Domain\Race\Entity;

final class AidStationCheckpoint extends Checkpoint
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
            $metricsFromStart,
            $race,
        );
        $this->validate();
    }

    public function update(
        string $name,
        string $location,
        MetricsFromStart $metricsFromStart,
    ): void {
        $this->setName($name);
        $this->setLocation($location);
        $this->setMetricsFromStart($metricsFromStart);
    }

    public function validate(): void
    {
        if (CheckpointType::AidStation !== $this->getCheckpointType()) {
            throw new \DomainException('Invalid Checkpoint Type');
        }
    }

    public function getCheckpointType(): CheckpointType
    {
        return CheckpointType::AidStation;
    }
}
