<?php

namespace App\UI\Http\Rest\Food\Brand;

use App\Application\Food\UseCase\ListBrand\ListBrandQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands', name: 'api.brand.list', methods: ['GET'])]
class ListBrandController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            $this->queryBus->query(new ListBrandQuery()),
            Response::HTTP_OK
        );
    }
}
