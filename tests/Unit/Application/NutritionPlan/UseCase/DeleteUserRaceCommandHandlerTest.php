<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\DeleteUserRace\DeleteUserRaceCommand;
use App\Application\NutritionPlan\UseCase\DeleteUserRace\DeleteUserRaceCommandHandler;
use App\Domain\NutritionPlan\Entity\ImportedRace;
use App\Domain\NutritionPlan\Exception\ForbiddenRaceAccessException;
use App\Domain\NutritionPlan\Exception\RaceNotFoundException;
use App\Domain\NutritionPlan\Repository\RacesCatalog;
use PHPUnit\Framework\TestCase;

final class DeleteUserRaceCommandHandlerTest extends TestCase
{
    public function testDeleteRaceSuccessfully(): void
    {
        $runnerId = 'runner-123';
        $raceId = 'race-456';

        $race = new ImportedRace(
            id: $raceId,
            runnerId: $runnerId,
            externalRaceId: 'ext-race-1',
            externalEventId: 'ext-event-1',
            name: 'Test Race',
            distance: 42195,
            ascent: 1000,
            descent: 1000,
            startDateTime: new \DateTimeImmutable('2024-06-15 08:00:00'),
            location: 'Paris',
        );

        $racesCatalog = $this->createMock(RacesCatalog::class);
        $racesCatalog->expects($this->once())
            ->method('get')
            ->with($raceId)
            ->willReturn($race);

        $racesCatalog->expects($this->once())
            ->method('remove')
            ->with($race);

        $handler = new DeleteUserRaceCommandHandler($racesCatalog);
        $command = new DeleteUserRaceCommand($runnerId, $raceId);

        ($handler)($command);
    }

    public function testDeleteRaceThrowsExceptionWhenRaceDoesNotBelongToUser(): void
    {
        $runnerId = 'runner-123';
        $otherRunnerId = 'runner-789';
        $raceId = 'race-456';

        $race = new ImportedRace(
            id: $raceId,
            runnerId: $otherRunnerId,
            externalRaceId: 'ext-race-1',
            externalEventId: 'ext-event-1',
            name: 'Test Race',
            distance: 42195,
            ascent: 1000,
            descent: 1000,
            startDateTime: new \DateTimeImmutable('2024-06-15 08:00:00'),
            location: 'Paris',
        );

        $racesCatalog = $this->createMock(RacesCatalog::class);
        $racesCatalog->expects($this->once())
            ->method('get')
            ->with($raceId)
            ->willReturn($race);

        $racesCatalog->expects($this->never())
            ->method('remove');

        $handler = new DeleteUserRaceCommandHandler($racesCatalog);
        $command = new DeleteUserRaceCommand($runnerId, $raceId);

        $this->expectException(ForbiddenRaceAccessException::class);
        $this->expectExceptionMessage(\sprintf('Runner %s cannot access race %s', $runnerId, $raceId));

        ($handler)($command);
    }

    public function testDeleteRaceThrowsExceptionWhenRaceNotFound(): void
    {
        $runnerId = 'runner-123';
        $raceId = 'race-not-found';

        $racesCatalog = $this->createMock(RacesCatalog::class);
        $racesCatalog->expects($this->once())
            ->method('get')
            ->with($raceId)
            ->willThrowException(new RaceNotFoundException($raceId));

        $racesCatalog->expects($this->never())
            ->method('remove');

        $handler = new DeleteUserRaceCommandHandler($racesCatalog);
        $command = new DeleteUserRaceCommand($runnerId, $raceId);

        $this->expectException(RaceNotFoundException::class);

        ($handler)($command);
    }
}
