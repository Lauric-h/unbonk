<?php

namespace App\UI\Http\Web\Food\Brand;

use App\Application\Food\UseCase\ListBrand\ListBrandQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/brands', name: 'app.brand.list')]
final class ListBrandController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(): Response
    {
        return $this->render('Food/list_brand.html.twig', [
            'list' => $this->queryBus->query(new ListBrandQuery()),
        ]);
    }
}
