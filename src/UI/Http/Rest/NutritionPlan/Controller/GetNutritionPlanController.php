<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\GetNutritionPlan\GetNutritionPlanQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nutrition-plans/{nutritionPlanId}', name: 'app.nutrition_plan.get', methods: ['GET'])]
final class GetNutritionPlanController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(string $nutritionPlanId): JsonResponse
    {
        return new JsonResponse(
            $this->queryBus->query(new GetNutritionPlanQuery($nutritionPlanId)),
            Response::HTTP_OK
        );
    }
}
