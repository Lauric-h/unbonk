<?php

namespace App\UI\Http\Web\Food\Brand;

use App\Application\Food\UseCase\GetBrand\GetBrandQuery;
use App\Application\Food\UseCase\ListFood\ListFoodQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands/{id}', name: 'app.brand.get', methods: ['GET'])]
final class GetBrandController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(string $id): Response
    {
        return $this->render('Food/get_brand.html.twig', [
            'brand' => $this->queryBus->query(new GetBrandQuery($id)),
            'foods' => $this->queryBus->query(new ListFoodQuery($id)),
        ]);
    }
}
