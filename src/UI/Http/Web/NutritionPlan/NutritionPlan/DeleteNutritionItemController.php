<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\NutritionPlan;

use App\Application\NutritionPlan\UseCase\DeleteNutritionItem\DeleteNutritionItemCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/nutrition-plans/{nutritionPlanId}/segments/{segmentId}/items/{itemId}/delete', name: 'app.nutrition_plan.delete_item', methods: ['POST'])]
#[IsGranted('EDIT', subject: 'nutritionPlan')]
final class DeleteNutritionItemController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'nutritionPlanId')]
        NutritionPlan $nutritionPlan,
        string $segmentId,
        string $itemId,
    ): Response {
        $this->commandBus->dispatch(new DeleteNutritionItemCommand(
            nutritionPlanId: $nutritionPlan->id,
            segmentId: $segmentId,
            nutritionItemId: $itemId,
        ));

        $this->addFlash('success', 'Aliment supprimé avec succès !');

        return $this->redirectToRoute('app.nutrition_plan.edit', ['nutritionPlanId' => $nutritionPlan->id]);
    }
}
