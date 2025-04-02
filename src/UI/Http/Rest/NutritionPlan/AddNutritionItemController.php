<?php

namespace App\UI\Http\Rest\NutritionPlan;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class AddNutritionItemController extends AbstractController
{
    public function __construct()
    {
    }

    public function __invoke(): JsonResponse
    {
        // TODO
        return new JsonResponse([]);
    }
}