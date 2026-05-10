<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\GetNutritionPlan\GetNutritionPlanQuery;
use App\Domain\NutritionPlan\Entity\NutritionPlan;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/nutrition-plans/{nutritionPlanId}', name: 'api.nutrition_plan.get', methods: ['GET'])]
final class GetNutritionPlanController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
    ) {
    }

    #[IsGranted('VIEW', subject: 'nutritionPlan')]
    public function __invoke(
        NutritionPlan $nutritionPlan,
        #[CurrentUser]
        UserAdapter $userAdapter
    ): JsonResponse {
        return new JsonResponse(
            $this->queryBus->query(new GetNutritionPlanQuery($nutritionPlan->id)),
            Response::HTTP_OK
        );
    }
}
