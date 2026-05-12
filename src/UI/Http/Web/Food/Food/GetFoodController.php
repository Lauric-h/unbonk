<?php

namespace App\UI\Http\Web\Food\Food;

use App\Application\Food\UseCase\GetFood\GetFoodQuery;
use App\Domain\Food\Entity\Food;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/foods/{id}', name: 'app.food.get')]
class GetFoodController extends AbstractController
{
    public function __construct(private readonly QueryBus $queryBus)
    {
    }

    public function __invoke(
        #[MapEntity(id: 'id')]
        Food $food
    ): Response {
        return $this->render('food/get_food.html.twig', [
            'food' => $this->queryBus->query(new GetFoodQuery($food->id)),
        ]);
    }
}
