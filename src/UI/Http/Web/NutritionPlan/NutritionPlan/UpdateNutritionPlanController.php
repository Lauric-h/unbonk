<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\NutritionPlan;

use App\Application\NutritionPlan\UseCase\UpdateNutritionPlan\UpdateNutritionPlanCommand;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Web\NutritionPlan\Form\NutritionPlan\UpdateNutritionPlanModel;
use App\UI\Http\Web\NutritionPlan\Form\NutritionPlan\UpdateNutritionPlanType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/nutrition-plans/{nutritionPlanId}/rename', name: 'app.nutrition_plan.rename', methods: ['GET', 'POST'])]
#[IsGranted('EDIT', subject: 'nutritionPlan')]
final class UpdateNutritionPlanController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(
        Request $request,
        #[MapEntity(id: 'nutritionPlanId')] NutritionPlan $nutritionPlan,
    ): Response {
        $model = new UpdateNutritionPlanModel(
            name: $nutritionPlan->name,
        );

        $form = $this->createForm(UpdateNutritionPlanType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->dispatch(new UpdateNutritionPlanCommand(
                nutritionPlanId: $nutritionPlan->id,
                name: $model->name,
            ));

            $this->addFlash('success', 'Plan renommé avec succès !');

            return $this->redirectToRoute('app.nutrition_plan.get', ['nutritionPlanId' => $nutritionPlan->id]);
        }

        return $this->render('nutrition_plan/nutrition_plan/update_nutrition_plan.html.twig', [
            'form' => $form,
            'nutritionPlan' => $nutritionPlan,
        ]);
    }
}
