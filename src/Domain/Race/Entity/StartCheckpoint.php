<?php

namespace App\Domain\Race\Entity;

final class StartCheckpoint extends Checkpoint
{
    public const DEFAULT_NAME = 'start';

    public function __construct(
        string $id,
        string $name,
        string $location,
        Race $race,
    ) {
        $metricsFromStart = MetricsFromStart::create(0, 0, 0, 0);
        parent::__construct(
            $id,
            $name,
            $location,
            $metricsFromStart,
            $race
        );
        $this->validate();
    }

    public function update(string $name, string $location): void
    {
        $this->setName($name);
        $this->setLocation($location);
    }

    public function validate(): void
    {
        if (0 !== $this->getMetricsFromStart()->distance->value
            || 0 !== $this->getMetricsFromStart()->ascent->value
            || 0 !== $this->getMetricsFromStart()->descent->value
            || 0 !== $this->getMetricsFromStart()->estimatedTimeInMinutes->minutes
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

    public function willMetricsChange(MetricsFromStart $metrics): bool
    {
        return false;
    }
}
