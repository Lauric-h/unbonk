<?php

namespace App\Domain\Race\Entity;

use App\Domain\Shared\Entity\Ascent;
use App\Domain\Shared\Entity\Descent;
use App\Domain\Shared\Entity\Distance;
use App\Domain\Shared\Entity\Duration;

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
        $metricsFromStart = MetricsFromStart::create(
            duration: new Duration($estimatedTimeInMinutes),
            distance: new Distance($race->profile->distance),
            ascent: new Ascent($race->profile->ascent),
            descent: new Descent($race->profile->descent),
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
        $metrics = MetricsFromStart::create(
            new Duration($this->getMetricsFromStart()->estimatedTimeInMinutes),
            new Distance($profile->distance),
            new Ascent($profile->ascent),
            new Descent($profile->descent),
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
            || $this->getMetricsFromStart()->ascent !== $this->getRace()->profile->ascent
            || $this->getMetricsFromStart()->descent !== $this->getRace()->profile->descent
        ) {
            throw new \DomainException('Invalid Finish Checkpoint Metrics');
        }

        if (CheckpointType::Finish !== $this->getCheckpointType()) {
            throw new \DomainException('Invalid Checkpoint Type');
        }

        if (0 === $this->getMetricsFromStart()->estimatedTimeInMinutes) {
            throw new \DomainException('Cannot have FinishCheckpoint with 0 minute');
        }
    }

    public function getCheckpointType(): CheckpointType
    {
        return CheckpointType::Finish;
    }

    public function willMetricsChange(MetricsFromStart $metrics): bool
    {
        return false;
    }
}
