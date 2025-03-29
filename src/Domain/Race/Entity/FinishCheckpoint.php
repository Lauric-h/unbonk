<?php

namespace App\Domain\Race\Entity;

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

        $this->validate();
    }

    public function updateProfileMetrics(Profile $profile): void
    {
        $metrics = new MetricsFromStart(
            $this->getMetricsFromStart()->estimatedTimeInMinutes,
            $profile->distance,
            $profile->elevationGain,
            $profile->elevationLoss,
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
        if ($this->getMetricsFromStart()->distance !== $this->getRace()->profile->distance
            || $this->getMetricsFromStart()->elevationGain !== $this->getRace()->profile->elevationGain
            || $this->getMetricsFromStart()->elevationLoss !== $this->getRace()->profile->elevationLoss
        ) {
            throw new \DomainException('Invalid Finish Checkpoint Metrics');
        }

        if (CheckpointType::Finish !== $this->getCheckpointType()) {
            throw new \DomainException('Invalid Checkpoint Type');
        }

        /*
         * TODO
         * Uncomment when default duration is set
         */
        //        if ($this->getMetricsFromStart()->estimatedTimeInMinutes === 0) {
        //            throw new \DomainException('Cannot have FinishCheckpoint with 0 minute');
        //        }
    }

    public function getCheckpointType(): CheckpointType
    {
        return CheckpointType::Finish;
    }
}
