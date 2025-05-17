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

        if ($this->getMetricsFromStart()->distance->value >= $this->getRace()->profile->distance->value
            || 0 === $this->getMetricsFromStart()->distance->value
        ) {
            throw new \DomainException(\sprintf('Invalid Checkpoint Distance: %d', $this->getMetricsFromStart()->distance->value));
        }

        if ($this->getMetricsFromStart()->ascent->value > $this->getRace()->profile->ascent->value) {
            throw new \DomainException(\sprintf('Invalid Checkpoint elevation gain: %d', $this->getMetricsFromStart()->ascent->value));
        }

        if ($this->getMetricsFromStart()->descent->value > $this->getRace()->profile->descent->value) {
            throw new \DomainException(\sprintf('Invalid Checkpoint elevation loss: %d', $this->getMetricsFromStart()->descent->value));
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
