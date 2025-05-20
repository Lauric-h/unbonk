<?php

namespace App\UI\Http\Rest\Food\Food;

use App\Application\Food\UseCase\ListFood\ListFoodQuery;
use App\Domain\Food\Entity\IngestionType;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands/{brandId}/foods', name: 'api.food.list', methods: ['GET'])]
final class ListFoodController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(
        string $brandId,
        #[MapQueryParameter] ?string $name,
        #[MapQueryParameter] ?IngestionType $ingestionType,
    ): JsonResponse {
        return new JsonResponse(
            $this->queryBus->query(new ListFoodQuery($brandId, $name, $ingestionType)),
            Response::HTTP_OK,
        );
    }
}
