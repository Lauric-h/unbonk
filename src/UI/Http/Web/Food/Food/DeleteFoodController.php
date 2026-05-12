<?php

namespace App\UI\Http\Web\Food\Food;

use App\Application\Food\UseCase\DeleteFood\DeleteFoodCommand;
use App\Domain\Food\Entity\Food;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/foods/{id}', name: 'app.food.delete', methods: ['DELETE'])]
final class DeleteFoodController extends AbstractController
{
    public function __construct(private readonly CommandBus $bus)
    {
    }

    public function __invoke(
        #[MapEntity(id: 'id')]
        Food $food
    ): Response {
        $this->bus->dispatch(new DeleteFoodCommand($food->id));

        return $this->redirectToRoute('app.food.list');
    }
}
