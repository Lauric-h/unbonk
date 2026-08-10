<?php

namespace App\UI\Http\Web\NutritionPlan;

use App\Application\NutritionPlan\Exception\NutritionPlanAccessDeniedException;
use App\Application\NutritionPlan\UseCase\EditNutritionPlan\EditNutritionPlanCommand;
use App\Application\NutritionPlan\UseCase\GetMyNutritionPlan\GetMyNutritionPlanQuery;
use App\Domain\NutritionPlan\Exception\NutritionPlanNotFoundException;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\Infrastructure\User\Security\UserAdapter;
use App\UI\Http\Web\NutritionPlan\Form\EditNutritionPlanFormType;
use App\UI\Http\Web\NutritionPlan\Form\EditNutritionPlanModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('//nutrition-plans/{id}/edit', name: 'app.nutrition-plans.edit')]
final class EditNutritionPlanController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus, private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(
        #[CurrentUser] UserAdapter $userAdapter,
        string $id,
        Request $request
    ): Response
    {
        try {
            $nutritionPlan = $this->queryBus->query(new GetMyNutritionPlanQuery(
                runnerId: $userAdapter->getUser()->id,
                nutritionPlanId: $id,
            ));
        } catch (NutritionPlanNotFoundException) {
            $this->createNotFoundException();
        }

        $nutritionPlanEditModel = new EditNutritionPlanModel($nutritionPlan->getName());
        $form = $this->createForm(EditNutritionPlanFormType::class, $nutritionPlanEditModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->commandBus->dispatch(new EditNutritionPlanCommand(
                    id: $id,
                    runnerId: $userAdapter->getUser()->id,
                    name: $nutritionPlanEditModel->name
                ));

                $this->addFlash('success', 'NP edited successfully');
                return $this->redirectToRoute('app.nutrition_plan.get', ['nutritionPlanId' => $id]);
            } catch (NutritionPlanAccessDeniedException $e) {
                $this->addFlash('error', 'Could not edit NP');
                return $this->redirectToRoute('app.nutrition_plan.list');
            }
        }

        return $this->render('nutrition_plan/edit_nutrition_plan.html.twig', [
            'form' => $form,
        ]);
    }
}