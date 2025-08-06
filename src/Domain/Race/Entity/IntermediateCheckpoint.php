<?php

namespace App\Domain\Race\Entity;

final class IntermediateCheckpoint extends Checkpoint
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
        if (CheckpointType::Intermediate !== $this->getCheckpointType()) {
            throw new \DomainException('Invalid Checkpoint Type');
        }

        if ($this->getMetricsFromStart()->distance >= $this->getRace()->profile->distance
            || $this->getMetricsFromStart()->distance <= 0
        ) {
            throw new \DomainException(\sprintf('Invalid Checkpoint Distance: %d', $this->getMetricsFromStart()->distance));
        }

        if ($this->getMetricsFromStart()->ascent > $this->getRace()->profile->ascent
            || $this->getMetricsFromStart()->ascent < 0
        ) {
            throw new \DomainException(\sprintf('Invalid Checkpoint elevation gain: %d', $this->getMetricsFromStart()->ascent));
        }

        if ($this->getMetricsFromStart()->descent > $this->getRace()->profile->descent
            || $this->getMetricsFromStart()->descent < 0
        ) {
            throw new \DomainException(\sprintf('Invalid Checkpoint elevation loss: %d', $this->getMetricsFromStart()->descent));
        }
    }

    public function getCheckpointType(): CheckpointType
    {
        return CheckpointType::Intermediate;
    }

    public function willMetricsChange(MetricsFromStart $metrics): bool
    {
        return !$this->getMetricsFromStart()->equals($metrics);
    }
}
