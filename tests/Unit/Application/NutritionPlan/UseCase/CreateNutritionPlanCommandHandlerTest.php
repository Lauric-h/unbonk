<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\Factory\ImportedRaceFactory;
use App\Application\NutritionPlan\UseCase\CreateNutritionPlan\CreateNutritionPlanCommand;
use App\Application\NutritionPlan\UseCase\CreateNutritionPlan\CreateNutritionPlanCommandHandler;
use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\DTO\ExternalRaceDTO;
use App\Domain\NutritionPlan\Port\ExternalRacePort;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Tests\Unit\MockIdGenerator;
use PHPUnit\Framework\TestCase;

final class CreateNutritionPlanCommandHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $id = 'nutrition-plan-id';
        $externalRaceId = 'external-race-id';
        $runnerId = 'runner-id';

        $command = new CreateNutritionPlanCommand($id, $externalRaceId, $runnerId);

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

        $externalRaceApi = $this->createMock(ExternalRacePort::class);
        $externalRaceApi->expects($this->once())
            ->method('getRaceDetails')
            ->with($externalRaceId)
            ->willReturn($externalRace);

        $repository = $this->createMock(NutritionPlansCatalog::class);
        $repository->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($nutritionPlan) use ($id, $runnerId): bool {
                $this->assertSame($id, $nutritionPlan->id);
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

        $handler = new CreateNutritionPlanCommandHandler(
            $repository,
            $externalRaceApi,
            $importedRaceFactory,
            $idGenerator,
        );

        ($handler)($command);
    }

    public function testHandleThrowsExceptionWhenRaceNotFound(): void
    {
        $command = new CreateNutritionPlanCommand('id', 'non-existent-race-id', 'runner-id');

        $externalRaceApi = $this->createMock(ExternalRacePort::class);
        $externalRaceApi->expects($this->once())
            ->method('getRaceDetails')
            ->with('non-existent-race-id')
            ->willReturn(null);

        $repository = $this->createMock(NutritionPlansCatalog::class);
        $repository->expects($this->never())->method('add');

        $idGenerator = new MockIdGenerator('id');
        $importedRaceFactory = new ImportedRaceFactory($idGenerator);

        $handler = new CreateNutritionPlanCommandHandler(
            $repository,
            $externalRaceApi,
            $importedRaceFactory,
            $idGenerator,
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Race with id non-existent-race-id not found');

        ($handler)($command);
    }
}
