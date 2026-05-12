<?php

namespace App\UI\Http\Rest\Food\Brand;

use App\Application\Food\UseCase\GetBrand\GetBrandQuery;
use App\Domain\Food\Entity\Brand;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands/{id}', name: 'api.brand.get', methods: ['GET'])]
final class GetBrandController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(
        #[MapEntity(id: 'id')]
        Brand $brand
    ): JsonResponse {
        return new JsonResponse(
            $this->queryBus->query(new GetBrandQuery($brand->id)),
            Response::HTTP_OK,
        );
    }
}
