<?php

namespace App\UI\Http\Rest\Food\Food;

use App\Application\Food\DeleteFood\DeleteFoodCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DeleteFoodController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(string $id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteFoodCommand($id));

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
