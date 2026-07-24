<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\NutritionPlan\UseCase;

use App\Application\NutritionPlan\UseCase\CreateNutritionPlan\CreateNutritionPlanCommand;
use App\Application\NutritionPlan\UseCase\CreateNutritionPlan\CreateNutritionPlanCommandHandler;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\NutritionPlan\Repository\RunnerRacesCatalog;
use App\Tests\Unit\Fixture\NutritionPlanTestFixture;
use App\Tests\Unit\MockIdGenerator;
use PHPUnit\Framework\TestCase;

final class CreateNutritionPlanCommandHandlerTest extends TestCase
{
    public function testCreateNutritionPlan(): void
    {
        $nutritionPlansCatalog = $this->createMock(NutritionPlansCatalog::class);
        $racesCatalog = $this->createMock(RunnerRacesCatalog::class);
        $idGenerator = new MockIdGenerator('segment-plan-id');
        $runnerRace = NutritionPlanTestFixture::createDefaultRunnerRaceWithRunnerId('runner-123');

        $handler = new CreateNutritionPlanCommandHandler(
            $nutritionPlansCatalog,
            $racesCatalog,
            $idGenerator
        );

        $racesCatalog->expects($this->once())
            ->method('get')
            ->with('race-id')
            ->willReturn($runnerRace);

        $nutritionPlansCatalog->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($nutritionPlan) use ($runnerRace): bool {
                $this->assertSame('nutrition-plan-id', $nutritionPlan->id);
                $this->assertSame('Plan A', $nutritionPlan->name);
                $this->assertSame($runnerRace, $nutritionPlan->runnerRace);
                $this->assertCount(2, $nutritionPlan->getSegmentPlans());

                return true;
            }));

        $command = new CreateNutritionPlanCommand(
            nutritionPlanId: 'nutrition-plan-id',
            RunnerRaceId: 'race-id',
            runnerId: 'runner-123',
            name: 'Plan A'
        );

        $handler($command);
    }

    public function testCreateNutritionPlanWithoutName(): void
    {
        $nutritionPlansCatalog = $this->createMock(NutritionPlansCatalog::class);
        $racesCatalog = $this->createMock(RunnerRacesCatalog::class);
        $idGenerator = new MockIdGenerator('segment-plan-id');
        $runnerRace = NutritionPlanTestFixture::createDefaultRunnerRaceWithRunnerId('runner-123');

        $handler = new CreateNutritionPlanCommandHandler(
            $nutritionPlansCatalog,
            $racesCatalog,
            $idGenerator
        );

        $racesCatalog->expects($this->once())
            ->method('get')
            ->with('race-id')
            ->willReturn($runnerRace);

        $nutritionPlansCatalog->expects($this->once())
            ->method('add')
            ->with($this->callback(function ($nutritionPlan) use ($runnerRace): bool {
                $this->assertSame('nutrition-plan-id', $nutritionPlan->id);
                $this->assertNull($nutritionPlan->name);
                $this->assertSame($runnerRace, $nutritionPlan->runnerRace);

                return true;
            }));

        $command = new CreateNutritionPlanCommand(
            nutritionPlanId: 'nutrition-plan-id',
            RunnerRaceId: 'race-id',
            runnerId: 'runner-123'
        );

        $handler($command);
    }
}
