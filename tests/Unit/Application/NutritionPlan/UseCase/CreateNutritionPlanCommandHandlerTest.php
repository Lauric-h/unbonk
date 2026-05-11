<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\CreateNutritionPlan\CreateNutritionPlanCommand;
use App\Application\NutritionPlan\UseCase\CreateNutritionPlan\CreateNutritionPlanCommandHandler;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\NutritionPlan\Repository\RacesCatalog;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use App\Tests\Unit\MockIdGenerator;
use PHPUnit\Framework\TestCase;

final class CreateNutritionPlanCommandHandlerTest extends TestCase
{
    public function testCreateNutritionPlan(): void
    {
        $nutritionPlansCatalog = $this->createMock(NutritionPlansCatalog::class);
        $racesCatalog = $this->createMock(RacesCatalog::class);
        $idGenerator = new MockIdGenerator('segment-id');

        $handler = new CreateNutritionPlanCommandHandler(
            $nutritionPlansCatalog,
            $racesCatalog,
            $idGenerator
        );

        // Create an imported race
        $importedRace = NutritionPlanTestFixture::createDefaultImportedRaceWithRunnerId('runner-123');

        $racesCatalog->expects($this->once())
            ->method('get')
            ->with('race-id')
            ->willReturn($importedRace);

        $nutritionPlansCatalog->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($nutritionPlan): bool {
                // 3 checkpoints = 2 segments
                $this->assertSame('nutrition-plan-id', $nutritionPlan->id);
                $this->assertSame('Plan A', $nutritionPlan->name);
                $this->assertSame('imported-race-id', $nutritionPlan->race->id);
                $this->assertSame(2, $nutritionPlan->getSegments()->count());

                return true;
            }));

        $command = new CreateNutritionPlanCommand(
            nutritionPlanId: 'nutrition-plan-id',
            importedRaceId: 'race-id',
            runnerId: 'runner-123',
            name: 'Plan A'
        );

        $handler($command);
    }

    public function testCreateNutritionPlanThrowsExceptionWhenRaceDoesNotBelongToRunner(): void
    {
        $nutritionPlansCatalog = $this->createMock(NutritionPlansCatalog::class);
        $racesCatalog = $this->createMock(RacesCatalog::class);
        $idGenerator = new MockIdGenerator('segment-id');

        $handler = new CreateNutritionPlanCommandHandler(
            $nutritionPlansCatalog,
            $racesCatalog,
            $idGenerator
        );

        // Create an imported race for a different runner
        $importedRace = NutritionPlanTestFixture::createDefaultImportedRaceWithRunnerId('runner-456');

        $racesCatalog->expects($this->once())
            ->method('get')
            ->with('race-id')
            ->willReturn($importedRace);

        $nutritionPlansCatalog->expects($this->never())
            ->method('add');

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Race race-id does not belong to runner runner-123');

        $command = new CreateNutritionPlanCommand(
            nutritionPlanId: 'nutrition-plan-id',
            importedRaceId: 'race-id',
            runnerId: 'runner-123',
            name: 'Plan A'
        );

        $handler($command);
    }

    public function testCreateNutritionPlanWithoutName(): void
    {
        $nutritionPlansCatalog = $this->createMock(NutritionPlansCatalog::class);
        $racesCatalog = $this->createMock(RacesCatalog::class);
        $idGenerator = new MockIdGenerator('segment-id');

        $handler = new CreateNutritionPlanCommandHandler(
            $nutritionPlansCatalog,
            $racesCatalog,
            $idGenerator
        );

        $importedRace = NutritionPlanTestFixture::createDefaultImportedRaceWithRunnerId('runner-123');

        $racesCatalog->expects($this->once())
            ->method('get')
            ->with('race-id')
            ->willReturn($importedRace);

        $nutritionPlansCatalog->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($nutritionPlan): bool {
                $this->assertSame('nutrition-plan-id', $nutritionPlan->id);
                $this->assertNull($nutritionPlan->name);

                return true;
            }));

        $command = new CreateNutritionPlanCommand(
            nutritionPlanId: 'nutrition-plan-id',
            importedRaceId: 'race-id',
            runnerId: 'runner-123'
        );

        $handler($command);
    }
}
