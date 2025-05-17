<?php

namespace App\Domain\Race\Entity;

use App\Domain\Shared\Entity\Duration;

final class FinishCheckpoint extends Checkpoint
{
    public const DEFAULT_NAME = 'finish';

    public function __construct(
        string $id,
        string $name,
        string $location,
        int $estimatedTimeInMinutes,
        Race $race,
    ) {
        $metricsFromStart = new MetricsFromStart(
            estimatedTimeInMinutes: new Duration($estimatedTimeInMinutes),
            distance: $race->profile->distance,
            ascent: $race->profile->ascent,
            descent: $race->profile->descent,
        );

        parent::__construct(
            $id,
            $name,
            $location,
            $metricsFromStart,
            $race
        );

        $this->validate();
    }

    public function updateProfileMetrics(Profile $profile): void
    {
        $metrics = new MetricsFromStart(
            $this->getMetricsFromStart()->estimatedTimeInMinutes,
            $profile->distance,
            $profile->ascent,
            $profile->descent,
        );
        $this->setMetricsFromStart($metrics);
    }

    public function update(string $name, string $location): void
    {
        $this->setName($name);
        $this->setLocation($location);
    }

    public function validate(): void
    {
        if ($this->getMetricsFromStart()->distance->value !== $this->getRace()->profile->distance->value
            || $this->getMetricsFromStart()->ascent->value !== $this->getRace()->profile->ascent->value
            || $this->getMetricsFromStart()->descent->value !== $this->getRace()->profile->descent->value
        ) {
            throw new \DomainException('Invalid Finish Checkpoint Metrics');
        }

        if (CheckpointType::Finish !== $this->getCheckpointType()) {
            throw new \DomainException('Invalid Checkpoint Type');
        }

        if (0 === $this->getMetricsFromStart()->estimatedTimeInMinutes->minutes) {
            throw new \DomainException('Cannot have FinishCheckpoint with 0 minute');
        }
    }

    public function getCheckpointType(): CheckpointType
    {
        return CheckpointType::Finish;
    }

    public function willMetricsChange(MetricsFromStart $metrics): bool
    {
        return false;
    }
}
