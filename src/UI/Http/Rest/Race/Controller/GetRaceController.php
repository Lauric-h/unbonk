<?php

namespace App\UI\Http\Rest\Race\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/races/{id}', name: 'app.race.get', methods: ['POST'])]
final class GetRaceController extends AbstractController
{
    public function __construct()
    {
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse([]);
    }
}
