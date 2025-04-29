<?php

namespace App\Application\Race\ReadModel;

use App\Domain\Race\Entity\Checkpoint;

final class CheckpointReadModel
{
    public function __construct(
        public string $id,
        public string $name,
        public string $location,
        public string $checkpointType,
        public MetricsFromStartReadModel $metricsFromStart,
    ) {
    }

    public static function fromCheckpoint(Checkpoint $checkpoint): self
    {
        return new self(
            $checkpoint->getId(),
            $checkpoint->getName(),
            $checkpoint->getLocation(),
            $checkpoint->getCheckpointType()->value,
            MetricsFromStartReadModel::fromDomain($checkpoint->getMetricsFromStart())
        );
    }
}
