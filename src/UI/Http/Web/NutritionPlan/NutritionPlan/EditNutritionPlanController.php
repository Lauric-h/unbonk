<?php

declare(strict_types=1);

namespace App\UI\Http\Web\NutritionPlan\NutritionPlan;

use App\Application\NutritionPlan\UseCase\GetNutritionPlan\GetNutritionPlanQuery;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/nutrition-plans/{nutritionPlanId}/edit', name: 'app.nutrition_plan.edit', methods: ['GET'])]
#[IsGranted('EDIT', subject: 'nutritionPlan')]
final class EditNutritionPlanController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'nutritionPlanId')]
        NutritionPlan $nutritionPlan,
    ): Response {
        $nutritionPlanReadModel = $this->queryBus->query(new GetNutritionPlanQuery(
            id: $nutritionPlan->id,
        ));

        return $this->render('nutrition_plan/nutrition_plan/edit_nutrition_plan.html.twig', [
            'nutritionPlan' => $nutritionPlanReadModel,
        ]);
    }
}
