<?php

namespace App\UI\Http\Rest\Race\Controller;

use App\Application\Race\UseCase\DeleteRace\DeleteRaceCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/races/{id}', name: 'app.race.delete', methods: ['DELETE'])]
final class DeleteRaceController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(string $id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteRaceCommand($id));

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
