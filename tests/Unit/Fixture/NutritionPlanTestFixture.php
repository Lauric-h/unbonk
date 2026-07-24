<?php

declare(strict_types=1);

namespace App\Tests\Unit\Fixture;

use App\Domain\NutritionPlan\Entity\Checkpoint;
use App\Domain\NutritionPlan\Entity\CheckpointType;
use App\Domain\NutritionPlan\Entity\Cutoff;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Entity\RunnerRace;

final class NutritionPlanTestFixture
{
    private string $id = 'nutrition-plan-id';
    private string $runnerId = 'runner-id';
    private ?string $name = null;
    private ?RunnerRace $runnerRace = null;

    public function withId(string $id): self
    {
        $clone = clone $this;
        $clone->id = $id;

        return $clone;
    }

    public function withRunnerId(string $runnerId): self
    {
        $clone = clone $this;
        $clone->runnerId = $runnerId;

        return $clone;
    }

    public function withName(string $name): self
    {
        $clone = clone $this;
        $clone->name = $name;

        return $clone;
    }

    public function withRunnerRace(RunnerRace $runnerRace): self
    {
        $clone = clone $this;
        $clone->runnerRace = $runnerRace;

        return $clone;
    }

    public function build(): NutritionPlan
    {
        $runnerRace = $this->runnerRace ?? self::createDefaultRunnerRaceWithRunnerId($this->runnerId);
        $segmentPlanIdCounter = 0;

        return NutritionPlan::createFromRunnerRace(
            id: $this->id,
            runnerRace: $runnerRace,
            name: $this->name,
            idGenerator: static function () use (&$segmentPlanIdCounter): string {
                return 'segment-plan-id-'.++$segmentPlanIdCounter;
            },
        );
    }

    public static function createDefaultRunnerRace(): RunnerRace
    {
        return self::createDefaultRunnerRaceWithRunnerId('runner-id');
    }

    public static function createDefaultRunnerRaceWithRunnerId(string $runnerId): RunnerRace
    {
        $segmentIdCounter = 0;

        $runnerRace = new RunnerRace(
            id: 'runner-race-id',
            runnerId: $runnerId,
            sourceRaceId: 'external-race-id',
            eventId: 'external-event-id',
            eventName: 'Test Event',
            name: 'Test Event',
            distance: 50000,
            ascent: 2000,
            descent: 1500,
            startDateTime: new \DateTimeImmutable('2024-06-01 06:00:00'),
            location: 'Mountain Town',
            segmentIdGenerator: static function () use (&$segmentIdCounter): string {
                return 'segment-id-'.++$segmentIdCounter;
            },
        );

        $runnerRace->addCheckpoint(new Checkpoint(
            id: 'start-checkpoint-id',
            runnerRace: $runnerRace,
            externalCheckpointId: 'start',
            name: 'Start',
            location: 'Mountain Town',
            distanceFromStart: 0,
            ascentFromStart: 0,
            descentFromStart: 0,
            cutoff: null,
            assistanceAllowed: false,
            type: CheckpointType::StartCheckpoint,
        ));

        $runnerRace->addCheckpoint(new Checkpoint(
            id: 'aid-station-id',
            runnerRace: $runnerRace,
            externalCheckpointId: 'aid-1',
            name: 'Aid Station 1',
            location: 'Valley',
            distanceFromStart: 25000,
            ascentFromStart: 1000,
            descentFromStart: 750,
            cutoff: new Cutoff(new \DateTimeImmutable('2024-06-01 12:00:00')),
            assistanceAllowed: true,
            type: CheckpointType::AidStation,
        ));

        $runnerRace->addCheckpoint(new Checkpoint(
            id: 'finish-checkpoint-id',
            runnerRace: $runnerRace,
            externalCheckpointId: 'finish',
            name: 'Finish',
            location: 'Mountain Town',
            distanceFromStart: 50000,
            ascentFromStart: 2000,
            descentFromStart: 1500,
            cutoff: null,
            assistanceAllowed: false,
            type: CheckpointType::FinishCheckpoint,
        ));

        return $runnerRace;
    }
}
