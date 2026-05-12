<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller\NutritionPlan;

use App\Application\NutritionPlan\UseCase\ListNutritionPlans\ListNutritionPlansQuery;
use App\Application\Shared\Security\CurrentUserIdProvider;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nutrition-plans', name: 'api.nutrition_plan.list', methods: ['GET'])]
final class ListNutritionPlansController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CurrentUserIdProvider $currentUserIdProvider,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            $this->queryBus->query(new ListNutritionPlansQuery($this->currentUserIdProvider->getCurrentUserId())),
            Response::HTTP_OK
        );
    }
}
