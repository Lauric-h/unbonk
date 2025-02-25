<?php

namespace App\UI\Http\Rest\Food\Food;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands/{brandId}/foods/{id}', name: 'app.brand.food.get', methods: ['GET'])]
final class GetFoodController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    }
}
