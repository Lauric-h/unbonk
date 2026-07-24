<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller\Race;

use App\Application\NutritionPlan\UseCase\ListRunnerRaces\ListRunnerRacesQuery;
use App\Application\Shared\Security\CurrentUserIdProvider;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/races', name: 'api.races.list', methods: ['GET'])]
final class ListRunnerRacesController extends AbstractController
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly CurrentUserIdProvider $currentUserIdProvider,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            $this->queryBus->query(new ListRunnerRacesQuery($this->currentUserIdProvider->getCurrentUserId())),
            Response::HTTP_OK
        );
    }
}
