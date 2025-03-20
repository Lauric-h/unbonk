<?php

namespace App\UI\Http\Rest\Race\Controller;

use App\Application\Race\UseCase\ListRace\ListRaceQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/races', name: 'app.race.list', methods: ['GET'])]
final class ListRaceController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            /* @phpstan-ignore-next-line */
            $this->queryBus->query(new ListRaceQuery($this->getUser()->getUser()->id)),
            Response::HTTP_OK
        );
    }
}
