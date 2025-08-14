<?php

namespace App\Infrastructure\Race\DTO;

use App\Domain\Race\Entity\Checkpoint;

final class CheckpointDTO
{
    public function __construct(
        public string $id,
        public string $name,
        public string $location,
        public int $distance,
        public int $estimatedDuration,
        public int $ascent,
        public int $descent,
    ) {
    }

    public static function fromDomain(Checkpoint $checkpoint): self
    {
        return new self(
            $checkpoint->getId(),
            $checkpoint->getName(),
            $checkpoint->getLocation(),
            $checkpoint->getMetricsFromStart()->distance,
            $checkpoint->getMetricsFromStart()->estimatedTimeInMinutes,
            $checkpoint->getMetricsFromStart()->ascent,
            $checkpoint->getMetricsFromStart()->descent,
        );
    }
}
