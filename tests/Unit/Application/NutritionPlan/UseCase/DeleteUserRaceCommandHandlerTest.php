<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\DeleteUserRace\DeleteRunnerRaceCommand;
use App\Application\NutritionPlan\UseCase\DeleteUserRace\DeleteRunnerRaceCommandHandler;
use App\Domain\NutritionPlan\Entity\ImportedRace;
use App\Domain\NutritionPlan\Exception\RaceNotFoundException;
use App\Domain\NutritionPlan\Repository\RunnerRacesCatalog;
use PHPUnit\Framework\TestCase;

final class DeleteUserRaceCommandHandlerTest extends TestCase
{
    public function testDeleteRaceSuccessfully(): void
    {
        $raceId = 'race-456';

        $race = new ImportedRace(
            id: $raceId,
            runnerId: 'runner-123',
            externalRaceId: 'ext-race-1',
            externalEventId: 'ext-event-1',
            eventName: 'Test Event',
            name: 'Test Event',
            distance: 42195,
            ascent: 1000,
            descent: 1000,
            startDateTime: new \DateTimeImmutable('2024-06-15 08:00:00'),
            location: 'Paris',
        );

        $racesCatalog = $this->createMock(RunnerRacesCatalog::class);
        $racesCatalog->expects($this->once())
            ->method('get')
            ->with($raceId)
            ->willReturn($race);

        $racesCatalog->expects($this->once())
            ->method('remove')
            ->with($race);

        $handler = new DeleteRunnerRaceCommandHandler($racesCatalog);
        $command = new DeleteRunnerRaceCommand($raceId);

        ($handler)($command);
    }

    public function testDeleteRaceThrowsExceptionWhenRaceNotFound(): void
    {
        $raceId = 'race-not-found';

        $racesCatalog = $this->createMock(RunnerRacesCatalog::class);
        $racesCatalog->expects($this->once())
            ->method('get')
            ->with($raceId)
            ->willThrowException(new RaceNotFoundException($raceId));

        $racesCatalog->expects($this->never())
            ->method('remove');

        $handler = new DeleteRunnerRaceCommandHandler($racesCatalog);
        $command = new DeleteRunnerRaceCommand($raceId);

        $this->expectException(RaceNotFoundException::class);

        ($handler)($command);
    }
}
