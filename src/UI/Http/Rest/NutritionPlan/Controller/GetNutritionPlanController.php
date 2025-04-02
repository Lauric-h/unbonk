<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/nutrition-plans/{nutritionPlanId}', name: 'app.nutrition_plan.get', methods: ['GET'])]
final class GetNutritionPlanController extends AbstractController
{
    public function __construct()
    {
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse();
    }
}
