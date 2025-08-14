<?php

namespace App\UI\Http\Rest\Race\Controller;

use App\Application\Race\UseCase\GetRace\GetRaceQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/races/{id}', name: 'api.race.get', methods: ['GET'])]
final class GetRaceController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(string $id): JsonResponse
    {
        /** @phpstan-ignore-next-line */
        $query = new GetRaceQuery($id, $this->getUser()->getUser()->id);

        return new JsonResponse(
            $this->queryBus->query($query),
            Response::HTTP_OK,
        );
    }
}
