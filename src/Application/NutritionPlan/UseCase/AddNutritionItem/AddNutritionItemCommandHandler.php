<?php

namespace App\Application\NutritionPlan\UseCase\AddNutritionItem;

use App\Application\Shared\IdGeneratorInterface;
use App\Domain\NutritionPlan\Entity\NutritionItem;
use App\Domain\NutritionPlan\Entity\Quantity;
use App\Domain\NutritionPlan\Port\ExternalFoodPort;
use App\Domain\NutritionPlan\Repository\NutritionPlansCatalog;
use App\Domain\Shared\Bus\CommandHandlerInterface;
use App\Domain\Shared\Entity\Carbs;

final readonly class AddNutritionItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private NutritionPlansCatalog $nutritionPlansCatalog,
        private IdGeneratorInterface $idGenerator,
        private ExternalFoodPort $externalFoodPort,
    ) {
    }

    public function __invoke(AddNutritionItemCommand $command): void
    {
        $externalFood = $this->externalFoodPort->getById($command->externalFoodId);
        $nutritionPlan = $this->nutritionPlansCatalog->get($command->nutritionPlanId);

        $segmentPlan = $nutritionPlan->getSegmentPlanBySegmentId($command->segmentId);

        if (null === $segmentPlan) {
            throw new \DomainException(sprintf('Segment plan for segment %s not found in nutrition plan %s', $command->segmentId, $command->nutritionPlanId));
        }

        $nutritionItem = new NutritionItem(
            id: $this->idGenerator->generate(),
            segmentNutritionPlan: $segmentPlan,
            foodItemId: $externalFood->reference,
            quantity: new Quantity($command->quantity),
            carbs: new Carbs($externalFood->carbs),
        );

        $segmentPlan->addItem($nutritionItem);

        $this->nutritionPlansCatalog->add($nutritionPlan);
    }
}
