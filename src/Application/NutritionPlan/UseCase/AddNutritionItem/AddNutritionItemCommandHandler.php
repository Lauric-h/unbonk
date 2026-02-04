<?php

namespace App\Application\NutritionPlan\UseCase\AddNutritionItem;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Port\ExternalFoodPort;
use App\Domain\NutritionPlan\Repository\SegmentsCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;
use App\Domain\Shared\Entity\Calories;
use App\Domain\Shared\Entity\Carbs;

final readonly class AddNutritionItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private SegmentsCatalog $segmentsCatalog,
        private IdGeneratorInterface $idGenerator,
        private ExternalFoodPort $externalFoodPort,
    ) {
    }

    public function __invoke(AddNutritionItemCommand $command): void
    {
        $externalFood = $this->externalFoodPort->getById($command->externalFoodId);
        $segment = $this->segmentsCatalog->get($command->segmentId);

        $nutritionItem = new NutritionItem(
            $this->idGenerator->generate(),
            $externalFood->reference,
            $externalFood->name,
            new Carbs($externalFood->carbs),
            new Quantity($command->quantity),
            new Calories($externalFood->calories ?? 0),
        );

        $segment->addNutritionItem($nutritionItem);

        $this->segmentsCatalog->add($segment);
    }
}
