<?php

declare(strict_types=1);

namespace App\Application\NutritionPlan\UseCase\AddCheckpoint;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\Entity\CustomCheckpoint;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\NutritionPlan\ValueObject\Cutoff;
use App\Domain\Shared\Bus\CommandHandlerInterface;

final readonly class AddCheckpointCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
        private IdGeneratorInterface $idGenerator,
    ) {
    }

    public function __invoke(AddCheckpointCommand $command): void
    {
        $nutritionPlan = $this->nutritionPlansCatalog->get($command->nutritionPlanId);

        $cutoff = null !== $command->cutoffTime
            ? new Cutoff($command->cutoffTime)
            : null;

        $checkpoint = new CustomCheckpoint(
            id: $this->idGenerator->generate(),
            name: $command->name,
            location: $command->location,
            distanceFromStart: $command->distanceFromStart,
            ascentFromStart: $command->ascentFromStart,
            descentFromStart: $command->descentFromStart,
            cutoff: $cutoff,
            assistanceAllowed: $command->assistanceAllowed,
            nutritionPlan: $nutritionPlan,
        );

        // Calculate the number of segments needed after adding the checkpoint
        $checkpointCount = \count($nutritionPlan->getAllCheckpoints()) + 1;
        $segmentIds = [];
        for ($i = 0; $i < $checkpointCount - 1; ++$i) {
            $segmentIds[] = $this->idGenerator->generate();
        }

        $nutritionPlan->addCustomCheckpoint($checkpoint, $segmentIds);

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }
}
