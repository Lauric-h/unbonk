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

        // Generate segment IDs based on imported checkpoint count
        $checkpointCount = \count($importedRace->getCheckpoints());
        $segmentIds = $this->generateSegmentIds($checkpointCount);

        $nutritionPlan = NutritionPlan::createFromImportedRace(
            id: $command->nutritionPlanId,
            race: $importedRace,
            segmentIds: $segmentIds,
            name: \sprintf('Nutrition plan for %s race', $externalRace->name),
        );

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }

    /**
     * Generate segment IDs based on checkpoint count.
     * Formula: segmentCount = checkpointCount - 1.
     *
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
