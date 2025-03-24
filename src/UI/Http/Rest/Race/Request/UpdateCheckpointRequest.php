<?php

namespace App\UI\Http\Rest\Race\Request;

use App\Domain\Race\Entity\CheckpointType;

final readonly class UpdateCheckpointRequest
{
    public function __construct(
        public string $name,
        public string $location,
        public CheckpointType $checkpointType,
        public int $estimatedTimeInMinutes,
        public int $distance,
        public int $elevationGain,
        public int $elevationLoss,
    ) {
    }
}
