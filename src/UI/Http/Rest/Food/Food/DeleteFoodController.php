<?php

namespace App\UI\Http\Rest\Food\Food;

use App\Application\Food\UseCase\DeleteFood\DeleteFoodCommand;
use App\Domain\Food\Entity\Food;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/foods/{id}', name: 'api.food.delete', methods: ['DELETE'])]
final class DeleteFoodController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(
        #[MapEntity(id: 'id')]
        Food $food
    ): JsonResponse {
        $this->commandBus->dispatch(new DeleteFoodCommand($food->id));

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
