<?php

namespace App\UI\Http\Rest\NutritionPlan\Controller;

use App\Application\NutritionPlan\UseCase\ListUserRaces\ListUserRacesQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\Infrastructure\User\Security\UserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/races', name: 'api.races.list', methods: ['GET'])]
final class ListUserRacesController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(#[CurrentUser] UserAdapter $userAdapter): JsonResponse
    {
        return new JsonResponse(
            $this->queryBus->query(new ListUserRacesQuery($userAdapter->getUser()->id)),
            Response::HTTP_OK
        );
    }
}
