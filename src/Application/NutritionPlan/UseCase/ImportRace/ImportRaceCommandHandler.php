<?php

namespace App\Application\NutritionPlan\UseCase\ImportRace;

use App\Application\NutritionPlan\Factory\RunnerRaceFactory;
use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Port\ExternalRacePort;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\NutritionPlan\Repository\RunnerRacesCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class ImportRaceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
        private RunnerRacesCatalog    $racesCatalog,
        private ExternalRacePort      $client,
        private RunnerRaceFactory     $RunnerRaceFactory,
    ) {
    }

    public function __invoke(ImportRaceCommand $command): void
    {
        $externalRace = $this->client->getRaceDetails($command->externalEventId, $command->externalRaceId);

        if (null === $externalRace) {
            throw new \DomainException(\sprintf('Race with id %s not found', $command->externalRaceId));
        }

        $RunnerRace = $this->RunnerRaceFactory->createFromExternalRace($externalRace, $command->runnerId);

        $this->racesCatalog->add($RunnerRace);

        $nutritionPlan = NutritionPlan::createFromRunnerRace(
            id: $command->nutritionPlanId,
            runnerRace: $RunnerRace,
            name: \sprintf('Nutrition plan for %s race', $externalRace->name),
        );

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }
}
