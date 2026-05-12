<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller\NutritionPlan;

use App\Application\NutritionPlan\UseCase\GetNutritionPlan\GetNutritionPlanQuery;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/nutrition-plans/{nutritionPlanId}', name: 'api.nutrition_plan.get', methods: ['GET'])]
#[IsGranted('EDIT', subject: 'nutritionPlan')]
final class GetNutritionPlanController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
    ) {
    }

    public function __invoke(
        #[MapEntity(id: 'nutritionPlanId')]
        NutritionPlan $nutritionPlan
    ): JsonResponse {
        return new JsonResponse(
            $this->queryBus->query(new GetNutritionPlanQuery($nutritionPlan->id)),
            Response::HTTP_OK
        );
    }
}
