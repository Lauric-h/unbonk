<?php

namespace App\Application\NutritionPlan\UseCase\ImportRace;

use App\Application\NutritionPlan\Factory\ImportedRaceFactory;
use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Domain\NutritionPlan\Port\ExternalRacePort;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\NutritionPlan\Repository\RacesCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class ImportRaceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
        private RacesCatalog $racesCatalog,
        private ExternalRacePort $client,
        private ImportedRaceFactory $importedRaceFactory,
        private IdGeneratorInterface $idGenerator,
    ) {
    }

    public function __invoke(ImportRaceCommand $command): void
    {
        $externalRace = $this->client->getRaceDetails($command->externalEventId, $command->externalRaceId);

        if (null === $externalRace) {
            throw new \DomainException(\sprintf('Race with id %s not found', $command->externalRaceId));
        }

        $importedRace = $this->importedRaceFactory->createFromExternalRace($externalRace, $command->runnerId);

        $this->racesCatalog->add($importedRace);

        $checkpointCount = \count($importedRace->getCheckpoints());
        $segmentIds = [];
        for ($i = 0; $i < $checkpointCount - 1; ++$i) {
            $segmentIds[] = $this->idGenerator->generate();
        }

        $nutritionPlan = NutritionPlan::createFromRace(
            id: $command->nutritionPlanId,
            race: $importedRace,
            segmentIds: $segmentIds,
        );

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }
}
