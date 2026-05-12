<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\CreateNutritionPlan;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\NutritionPlan\Repository\RacesCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class CreateNutritionPlanCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
        private RacesCatalog $racesCatalog,
        private IdGeneratorInterface $idGenerator,
    ) {
    }

    public function __invoke(CreateNutritionPlanCommand $command): void
    {
        $importedRace = $this->racesCatalog->get($command->importedRaceId);

        // Note: Ownership is already verified by ImportedRaceVoter at the HTTP layer
        // This handler focuses on business logic only

        $checkpointCount = \count($importedRace->getCheckpoints());
        $segmentIds = $this->generateSegmentIds($checkpointCount);

        $nutritionPlan = NutritionPlan::createFromImportedRace(
            id: $command->nutritionPlanId,
            race: $importedRace,
            segmentIds: $segmentIds,
            name: $command->name,
        );

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }

    /**
     * @return string[]
     */
    private function generateSegmentIds(int $checkpointCount): array
    {
        $segmentCount = $checkpointCount - 1;
        if ($segmentCount < 0) {
            return [];
        }

        $segmentIds = [];
        for ($i = 0; $i < $segmentCount; ++$i) {
            $segmentIds[] = $this->idGenerator->generate();
        }

        return $segmentIds;
    }
}
