<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\Factory\ImportedRaceFactory;
use App\Application\NutritionPlan\UseCase\ImportRace\ImportRaceCommand;
use App\Application\NutritionPlan\UseCase\ImportRace\ImportRaceCommandHandler;
use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;
use App\Domain\NutritionPlan\Port\ExternalRacePort;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Tests\Unit\MockIdGenerator;
use PHPUnit\Framework\TestCase;

final class ImportRaceCommandHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $nutritionPlanId = 'nutrition-plan-id';
        $externalEventId = 'external-event-id';
        $externalRaceId = 'external-race-id';
        $runnerId = 'runner-id';

        $command = new ImportRaceCommand($nutritionPlanId, $externalEventId, $externalRaceId, $runnerId);

        $externalRace = new ExternalRaceDTO(
            id: 'external-race-id',
            eventId: 'external-event-id',
            name: 'Test Race',
            distance: 50000,
            ascent: 2000,
            descent: 1500,
            startDateTime: new \DateTimeImmutable('2024-06-01 06:00:00'),
            url: null,
            startLocation: 'Start City',
            finishLocation: 'Finish City',
            aidStations: [],
        );

        $externalRacePort = $this->createMock(ExternalRacePort::class);
        $externalRacePort->expects($this->once())
            ->method('getRaceDetails')
            ->with($externalEventId, $externalRaceId)
            ->willReturn($externalRace);

        $repository = $this->createMock(NutritionPlansCatalog::class);
        $repository->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($nutritionPlan) use ($nutritionPlanId, $runnerId): bool {
                $this->assertSame($nutritionPlanId, $nutritionPlan->id);
                $this->assertSame($runnerId, $nutritionPlan->runnerId);
                $this->assertSame('Test Race', $nutritionPlan->importedRace->name);

                return true;
            }));

        $idGenerator = new class implements IdGeneratorInterface {
            private int $counter = 0;

            public function generate(): string
            {
                return 'generated-id-'.++$this->counter;
            }
        };

        $importedRaceFactory = new ImportedRaceFactory($idGenerator);

        $handler = new ImportRaceCommandHandler(
            $repository,
            $externalRacePort,
            $importedRaceFactory,
            $idGenerator,
        );

        ($handler)($command);
    }

    public function testHandleThrowsExceptionWhenRaceNotFound(): void
    {
        $externalEventId = 'external-event-id';
        $externalRaceId = 'non-existent-race-id';

        $command = new ImportRaceCommand('id', $externalEventId, $externalRaceId, 'runner-id');

        $externalRacePort = $this->createMock(ExternalRacePort::class);
        $externalRacePort->expects($this->once())
            ->method('getRaceDetails')
            ->with($externalEventId, $externalRaceId)
            ->willReturn(null);

        $repository = $this->createMock(NutritionPlansCatalog::class);
        $repository->expects($this->never())->method('add');

        $idGenerator = new MockIdGenerator('id');
        $importedRaceFactory = new ImportedRaceFactory($idGenerator);

        $handler = new ImportRaceCommandHandler(
            $repository,
            $externalRacePort,
            $importedRaceFactory,
            $idGenerator,
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Race with id non-existent-race-id not found');

        ($handler)($command);
    }
}
