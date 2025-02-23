<?php

namespace App\UI\Http\Rest\Food\Brand;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands/{id}', name: 'app.brand.get', methods: ['GET'])]
final class GetBrandController extends AbstractController
{
    public function __construct()
    {
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse([], \Symfony\Component\HttpFoundation\Response::HTTP_OK);
    }
}
