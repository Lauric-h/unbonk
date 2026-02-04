<?php

namespace App\Application\NutritionPlan\UseCase\CreateNutritionPlan;

use App\Application\NutritionPlan\Factory\ImportedRaceFactory;
use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Port\ExternalRaceApiPort;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class CreateNutritionPlanCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
        private ExternalRaceApiPort $externalRaceApi,
        private ImportedRaceFactory $importedRaceFactory,
        private IdGeneratorInterface $idGenerator,
    ) {
    }

    public function __invoke(CreateNutritionPlanCommand $command): void
    {
        $externalRace = $this->externalRaceApi->getRaceDetails($command->externalRaceId);

        if (null === $externalRace) {
            throw new \DomainException(\sprintf('Race with id %s not found', $command->externalRaceId));
        }

        $importedRace = $this->importedRaceFactory->createFromExternalRace($externalRace);

        // Generate segment IDs (one per checkpoint pair)
        $checkpointCount = \count($importedRace->getCheckpoints());
        $segmentIds = [];
        for ($i = 0; $i < $checkpointCount - 1; ++$i) {
            $segmentIds[] = $this->idGenerator->generate();
        }

        $nutritionPlan = NutritionPlan::createFromImportedRace(
            $command->id,
            $command->runnerId,
            $importedRace,
            $segmentIds,
        );

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }
}
