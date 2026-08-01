<?php

namespace App\Application\NutritionPlan\UseCase\CreateNutritionPlan;

use App\Application\NutritionPlan\Exception\NutritionPlanAlreadyExistsException;
use App\Application\Race\Exception\RunnerRaceAccessDeniedException;
use App\Application\Race\Port\RunnerRaceReaderInterface;
use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Repository\NutritionPlanRepositoryInterface;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class CreateNutritionPlanCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private RunnerRaceReaderInterface $runnerRaceReader,       // Acceptable outside dependency
        private NutritionPlanRepositoryInterface $planRepository,
        private IdGeneratorInterface $idGenerator,
    ) {
    }

    public function __invoke(CreateNutritionPlanCommand $command): void
    {
        $race = $this->runnerRaceReader->get($command->runnerRaceId);
        if ($race->runnerId !== $command->runnerId) {
            throw new RunnerRaceAccessDeniedException($command->runnerRaceId, $command->runnerId);
        }

        if ($this->planRepository->existsForRunnerRace($command->runnerRaceId)) {
            throw new NutritionPlanAlreadyExistsException($command->runnerRaceId);
        }

        $orderedSegmentIds = array_map(
            static fn ($segment) => $segment->id,
            $race->segments,
        );

        $plan = NutritionPlan::create(
            id: $this->idGenerator->generate(),
            runnerRaceId: $command->runnerRaceId,
            orderedSegmentIds: $orderedSegmentIds,
            idGenerator: fn () => $this->idGenerator->generate(),
            name: \sprintf('Nutrition Plan for %s - %s', $race->eventName, $race->name),
        );

        $this->planRepository->add($plan);
    }
}