<?php

namespace App\UI\Http\Web\Food\Food;

use App\Application\Food\UseCase\GetFood\GetFoodQuery;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/foods/{id}', name: 'app.food.get')]
class GetFoodController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(string $id): Response
    {
        return $this->render('Food/get_food.html.twig', [
            'food' => $this->queryBus->query(new GetFoodQuery($id)),
        ]);
    }
}
