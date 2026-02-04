<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\GetEvent\GetEventQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/events/{eventId}', name: 'api.events.get', methods: ['GET'])]
final class GetEventController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(string $eventId): JsonResponse
    {
        return new JsonResponse(
            $this->queryBus->query(new GetEventQuery($eventId)),
            Response::HTTP_OK
        );
    }
}
