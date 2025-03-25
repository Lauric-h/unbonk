<?php

namespace App\Domain\Race\Entity;

final class FinishCheckpoint extends Checkpoint
{
    public function __construct(
        string $id,
        string $name,
        string $location,
        int $estimatedTimeInMinutes,
        Race $race,
    ) {
        $metricsFromStart = new MetricsFromStart(
            estimatedTimeInMinutes: $estimatedTimeInMinutes,
            distance: $race->profile->distance,
            elevationGain: $race->profile->elevationGain,
            elevationLoss: $race->profile->elevationLoss,
        );

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
        if ($this->getMetricsFromStart()->distance !== $this->getRace()->profile->distance
            || $this->getMetricsFromStart()->elevationGain !== $this->getRace()->profile->elevationGain
            || $this->getMetricsFromStart()->elevationLoss !== $this->getRace()->profile->elevationLoss
        ) {
            throw new \DomainException('Invalid Finish Checkpoint Metrics');
        }

        if (CheckpointType::Finish !== $this->getCheckpointType()) {
            throw new \DomainException('Invalid Checkpoint Type');
        }
    }

    public function getCheckpointType(): CheckpointType
    {
        return CheckpointType::Finish;
    }
}
