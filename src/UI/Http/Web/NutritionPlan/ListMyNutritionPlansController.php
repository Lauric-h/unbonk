<?php

namespace App\UI\Http\Web\NutritionPlan;

use App\Application\NutritionPlan\Exception\NutritionPlanAccessDeniedException;
use App\Application\NutritionPlan\UseCase\GetMyNutritionPlan\GetMyNutritionPlanQuery;
use App\Application\NutritionPlan\UseCase\ListMyNutritionPlans\ListMyNutritionPlansQuery;
use App\Domain\NutritionPlan\Exception\NutritionPlanNotFoundException;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/nutrition-plans', name: 'app.nutrition_plan.list', methods: ['GET'])]
final class ListMyNutritionPlansController extends AbstractController
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    public function __invoke(
        #[CurrentUser] UserAdapter $userAdapter,
    ): Response
    {
        try {
            $nutritionPlans = $this->queryBus->query(new ListMyNutritionPlansQuery($userAdapter->getUser()->id,));
        } catch (NutritionPlanNotFoundException) {
            throw $this->createNotFoundException('Ce plan nutrition n\'existe pas.');
        } catch (NutritionPlanAccessDeniedException) {
            throw $this->createNotFoundException('Ce plan nutrition n\'existe pas.');
        }

        return $this->render('nutrition_plan/list_nutrition_plans.html.twig', [
            'nutrition_plans' => $nutritionPlans,
        ]);
    }
}