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

        if (true === $this->willMetricsChange($metricsFromStart)) {
            $this->setMetricsFromStart($metricsFromStart);
        }
    }

    public function validate(): void
    {
        if (CheckpointType::AidStation !== $this->getCheckpointType()) {
            throw new \DomainException('Invalid Checkpoint Type');
        }

        if ($this->getMetricsFromStart()->distance >= $this->getRace()->profile->distance
            || $this->getMetricsFromStart()->distance <= 0
        ) {
            throw new \DomainException(\sprintf('Invalid Checkpoint Distance: %d', $this->getMetricsFromStart()->distance));
        }

        if ($this->getMetricsFromStart()->elevationGain > $this->getRace()->profile->elevationGain
            || $this->getMetricsFromStart()->elevationGain < 0
        ) {
            throw new \DomainException(\sprintf('Invalid Checkpoint elevation gain: %d', $this->getMetricsFromStart()->elevationGain));
        }

        if ($this->getMetricsFromStart()->elevationLoss > $this->getRace()->profile->elevationLoss
            || $this->getMetricsFromStart()->elevationLoss < 0
        ) {
            throw new \DomainException(\sprintf('Invalid Checkpoint elevation loss: %d', $this->getMetricsFromStart()->elevationLoss));
        }
    }

    public function getCheckpointType(): CheckpointType
    {
        return CheckpointType::AidStation;
    }

    public function willMetricsChange(MetricsFromStart $metrics): bool
    {
        return !$this->getMetricsFromStart()->equals($metrics);
    }
}
