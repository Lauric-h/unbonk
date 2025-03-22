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
}
