<?php

namespace App\Domain\Race\Entity;

use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;

final class StartCheckpoint extends Checkpoint
{
    public const DEFAULT_NAME = 'start';

    public function __construct(
        string $id,
        string $name,
        string $location,
        Race $race,
    ) {
        $metricsFromStart = MetricsFromStart::create(new Duration(0), new Distance(0), new Ascent(0), new Descent(0));
        parent::__construct(
            $id,
            $name,
            $location,
            $metricsFromStart,
            $race
        );
        $this->validate();
    }

    public function update(string $name, string $location): void
    {
        $this->setName($name);
        $this->setLocation($location);
    }

    public function validate(): void
    {
        if (0 !== $this->getMetricsFromStart()->distance
            || 0 !== $this->getMetricsFromStart()->ascent
            || 0 !== $this->getMetricsFromStart()->descent
            || 0 !== $this->getMetricsFromStart()->estimatedTimeInMinutes
        ) {
            throw new \DomainException('Invalid Start Checkpoint Metrics');
        }

        if (CheckpointType::Start !== $this->getCheckpointType()) {
            throw new \DomainException('Invalid Checkpoint Type');
        }
    }

    public function getCheckpointType(): CheckpointType
    {
        return CheckpointType::Start;
    }

    public function willMetricsChange(MetricsFromStart $metrics): bool
    {
        return false;
    }
}
