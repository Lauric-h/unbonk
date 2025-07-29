<?php

namespace App\UI\Http\Rest\Food\Food;

use App\Application\Food\UseCase\GetFood\GetFoodQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/foods/{id}', name: 'api.food.get', methods: ['GET'])]
final class GetFoodController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(string $id): JsonResponse
    {
        return new JsonResponse(
            $this->queryBus->query(new GetFoodQuery($id)),
            Response::HTTP_OK
        );
    }
}
