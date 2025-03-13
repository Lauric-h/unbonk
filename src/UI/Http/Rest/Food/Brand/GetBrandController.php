<?php

namespace App\UI\Http\Rest\Food\Brand;

use App\Application\Food\UseCase\GetBrand\GetBrandQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands/{id}', name: 'app.brand.get', methods: ['GET'])]
final class GetBrandController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(string $id): JsonResponse
    {
        $brand = $this->queryBus->query(new GetBrandQuery($id));

        return new JsonResponse($brand, Response::HTTP_OK);
    }
}
