<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\ReadModel\CheckpointReadModel;
use App\Application\NutritionPlan\ReadModel\RunnerRaceReadModel;
use App\Application\NutritionPlan\ReadModel\SegmentReadModel;
use App\Application\NutritionPlan\UseCase\GetRunnerRace\GetRunnerRaceQuery;
use App\Application\NutritionPlan\UseCase\GetRunnerRace\GetRunnerRaceQueryHandler;
use App\Domain\NutritionPlan\Exception\RaceNotFoundException;
use App\Domain\NutritionPlan\Repository\RunnerRacesCatalog;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use PHPUnit\Framework\TestCase;

final class GetRunnerRaceQueryHandlerTest extends TestCase
{
    public function testGetRunnerRaceReturnsReadModel(): void
    {
        $racesCatalog = $this->createMock(RunnerRacesCatalog::class);
        $handler = new GetRunnerRaceQueryHandler($racesCatalog);

        $race = NutritionPlanTestFixture::createDefaultRunnerRace();
        $raceId = $race->id;

        $racesCatalog->expects($this->once())
            ->method('get')
            ->with($raceId)
            ->willReturn($race);

        $result = ($handler)(new GetRunnerRaceQuery($raceId));

        $this->assertInstanceOf(RunnerRaceReadModel::class, $result);
        $this->assertSame($race->id, $result->id);
        $this->assertSame($race->sourceRaceId, $result->externalRaceId);
        $this->assertSame($race->eventId, $result->externalEventId);
        $this->assertSame($race->name, $result->name);
        $this->assertSame($race->distance, $result->distance);
        $this->assertSame($race->ascent, $result->ascent);
        $this->assertSame($race->descent, $result->descent);
        $this->assertSame($race->startDateTime, $result->startDateTime);
        $this->assertSame($race->location, $result->location);
        $this->assertCount(3, $result->checkpoints);
        $this->assertCount(2, $result->segments);
        $this->assertInstanceOf(CheckpointReadModel::class, $result->checkpoints[0]);
        $this->assertInstanceOf(SegmentReadModel::class, $result->segments[0]);
    }

    public function testGetRunnerRaceThrowsWhenRaceNotFound(): void
    {
        $raceId = 'race-not-found';

        $racesCatalog = $this->createMock(RunnerRacesCatalog::class);
        $racesCatalog->expects($this->once())
            ->method('get')
            ->with($raceId)
            ->willThrowException(new RaceNotFoundException($raceId));

        $handler = new GetRunnerRaceQueryHandler($racesCatalog);

        $this->expectException(RaceNotFoundException::class);

        ($handler)(new GetRunnerRaceQuery($raceId));
    }
}
