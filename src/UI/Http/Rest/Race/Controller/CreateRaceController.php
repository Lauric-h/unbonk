<?php

namespace App\UI\Http\Rest\Race\Controller;

use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/races', name: 'app.race.create', methods: ['POST'])]
final class CreateRaceController extends AbstractController
{
    public function __construct(CommandBus $commandBus)
    {
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            [],
            Response::HTTP_CREATED,
            ['Location' => '']
        );
    }
}