<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\NutritionPlan;

use App\Application\NutritionPlan\UseCase\ListNutritionPlans\ListNutritionPlansQuery;
use App\Application\Shared\Security\CurrentUserIdProvider;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/nutrition-plans', name: 'app.nutrition_plan.list', methods: ['GET'])]
#[IsGranted('EDIT', subject: 'nutritionPlan')]
final class ListNutritionPlansController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CurrentUserIdProvider $currentUserIdProvider,
    ) {
    }

    public function __invoke(): Response
    {
        $nutritionPlans = $this->queryBus->query(new ListNutritionPlansQuery(
            runnerId: $this->currentUserIdProvider->getCurrentUserId(),
        ));

        return $this->render('nutrition_plan/nutrition_plan/list_nutrition_plans.html.twig', [
            'nutritionPlans' => $nutritionPlans,
        ]);
    }
}
