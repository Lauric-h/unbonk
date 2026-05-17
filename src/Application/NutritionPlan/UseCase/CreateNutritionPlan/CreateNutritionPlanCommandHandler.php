<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\CreateNutritionPlan;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\NutritionPlan\Repository\RunnerRacesCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class CreateNutritionPlanCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
        private RunnerRacesCatalog    $racesCatalog,
    ) {
    }

    public function __invoke(CreateNutritionPlanCommand $command): void
    {
        $runnerRace = $this->racesCatalog->get($command->RunnerRaceId);

        $checkpointCount = \count($runnerRace->orderedCheckpoints());

        $nutritionPlan = NutritionPlan::createFromRunnerRace(
            id: $command->nutritionPlanId,
            runnerRace: $runnerRace,
            name: $command->name,
        );

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }
}
