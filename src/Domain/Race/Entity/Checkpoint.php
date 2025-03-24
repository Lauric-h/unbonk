<?php

namespace App\Domain\Race\Entity;

class Checkpoint
{
    public function __construct(
        public string $id,
        public string $name,
        public string $location,
        public CheckpointType $checkpointType,
        public MetricsFromStart $metricsFromStart,
        public Race $race,
    ) {
    }

    public function updateProfileMetrics(Profile $profile): void
    {
        $estimatedTime = $this->metricsFromStart->estimatedTimeInMinutes;
        $metrics = new MetricsFromStart($estimatedTime, $profile->distance, $profile->elevationGain, $profile->elevationLoss);
        $this->metricsFromStart = $metrics;
    }

    public function update(
        string $name,
        string $location,
        CheckpointType $checkpointType,
        int $estimatedTimeInMinutes,
        int $distance,
        int $elevationGain,
        int $elevationLoss,
    ): void {
        $this->name = $name;
        $this->location = $location;

        if ($this->isStartCheckpoint()
            || $this->isFinishCheckpoint()
        ) {
            return;
        }

        $this->checkpointType = $checkpointType;
        $this->metricsFromStart = new MetricsFromStart($estimatedTimeInMinutes, $distance, $elevationGain, $elevationLoss);
    }

    public function isStartCheckpoint(): bool
    {
        return CheckpointType::Start === $this->checkpointType;
    }

    public function isFinishCheckpoint(): bool
    {
        return CheckpointType::Finish === $this->checkpointType;
    }
}
