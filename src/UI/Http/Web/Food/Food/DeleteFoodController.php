<?php

namespace App\UI\Http\Web\Food\Food;

use App\Application\Food\UseCase\DeleteFood\DeleteFoodCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/foods/{id}', name: 'app.food.delete', methods: ['DELETE'])]
final class DeleteFoodController extends AbstractController
{
    public function __construct(private readonly CommandBus $bus)
    {
    }

    public function __invoke(string $id): Response
    {
        $this->bus->dispatch(new DeleteFoodCommand($id));

        return $this->redirectToRoute('app.food.list');
    }
}
