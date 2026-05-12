<?php

namespace App\UI\Http\Web\Food\Brand;

use App\Application\Food\UseCase\GetBrand\GetBrandQuery;
use App\Application\Food\UseCase\ListFood\ListFoodQuery;
use App\Domain\Food\Entity\Brand;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands/{id}', name: 'app.brand.get', methods: ['GET'])]
final class GetBrandController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(
        #[MapEntity(id: 'id')]
        Brand $brand
    ): Response {
        return $this->render('food/get_brand.html.twig', [
            'brand' => $this->queryBus->query(new GetBrandQuery($brand->id)),
            'foods' => $this->queryBus->query(new ListFoodQuery($brand->id)),
        ]);
    }
}
