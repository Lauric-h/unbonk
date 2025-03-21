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
}
