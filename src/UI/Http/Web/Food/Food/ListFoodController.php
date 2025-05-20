<?php

namespace App\UI\Http\Web\Food\Food;

use App\Application\Food\UseCase\ListFood\ListFoodQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/foods', name: 'app.food.list')]
final class ListFoodController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(): Response
    {
        return $this->render('Food/list_food.html.twig', [
            'list' => $this->queryBus->query(new ListFoodQuery()),
        ]);
    }
}
