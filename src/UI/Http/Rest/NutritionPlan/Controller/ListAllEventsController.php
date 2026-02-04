<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\ListAllEvents\ListAllEventsQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/events', name: 'app.event.list', methods: ['GET'])]
final class ListAllEventsController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus
    ) {
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            $this->queryBus->query(new ListAllEventsQuery()),
            Response::HTTP_OK
        );
    }
}
