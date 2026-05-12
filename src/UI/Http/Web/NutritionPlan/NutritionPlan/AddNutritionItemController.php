<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\NutritionPlan;

use App\Application\NutritionPlan\UseCase\AddNutritionItem\AddNutritionItemCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/nutrition-plans/{nutritionPlanId}/segments/{segmentId}/items/add', name: 'app.nutrition_plan.add_item', methods: ['POST'])]
#[IsGranted('EDIT', subject: 'nutritionPlan')]
final class AddNutritionItemController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(
        Request $request,
        #[MapEntity(id: 'nutritionPlanId')]
        NutritionPlan $nutritionPlan,
        string $segmentId,
    ): Response {
        $foodId = (string) $request->request->get('foodId');
        $quantity = (int) $request->request->get('quantity', 1);

        if (!$foodId) {
            $this->addFlash('error', 'Veuillez sélectionner un aliment');

            return $this->redirectToRoute('app.nutrition_plan.edit', ['nutritionPlanId' => $nutritionPlan->id]);
        }

        $this->commandBus->dispatch(new AddNutritionItemCommand(
            externalFoodId: $foodId,
            nutritionPlanId: $nutritionPlan->id,
            segmentId: $segmentId,
            quantity: $quantity,
        ));

        $this->addFlash('success', 'Aliment ajouté avec succès !');

        return $this->redirectToRoute('app.nutrition_plan.edit', ['nutritionPlanId' => $nutritionPlan->id]);
    }
}
