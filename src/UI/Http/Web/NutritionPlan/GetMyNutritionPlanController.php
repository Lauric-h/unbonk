<?php

namespace App\UI\Http\Web\NutritionPlan;

use App\Application\NutritionPlan\Exception\NutritionPlanAccessDeniedException;
use App\Application\NutritionPlan\UseCase\GetMyNutritionPlan\GetMyNutritionPlanQuery;
use App\Domain\NutritionPlan\Exception\NutritionPlanNotFoundException;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/nutrition-plans/{nutritionPlanId}', name: 'app.nutrition_plan.get', methods: ['GET'])]
final class GetMyNutritionPlanController extends AbstractController
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    public function __invoke(
        #[CurrentUser] UserAdapter $userAdapter,
        string $nutritionPlanId,
    ): Response
    {
        try {
            $nutritionPlan = $this->queryBus->query(new GetMyNutritionPlanQuery(
                runnerId: $userAdapter->getUser()->id,
                nutritionPlanId: $nutritionPlanId
            ));
        } catch (NutritionPlanNotFoundException) {
            throw $this->createNotFoundException('Ce plan nutrition n\'existe pas.');
        } catch (NutritionPlanAccessDeniedException) {
            throw $this->createNotFoundException('Ce plan nutrition n\'existe pas.');
        }

        return $this->render('nutrition_plan/my_nutrition_plan.html.twig', [
            'nutrition_plan' => $nutritionPlan,
        ]);
    }
}