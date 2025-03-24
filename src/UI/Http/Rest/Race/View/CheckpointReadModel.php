<?php

namespace App\UI\Http\Rest\Race\View;

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
            $checkpoint->id,
            $checkpoint->name,
            $checkpoint->location,
            $checkpoint->checkpointType->value,
            MetricsFromStartReadModel::fromDomain($checkpoint->metricsFromStart)
        );
    }
}
