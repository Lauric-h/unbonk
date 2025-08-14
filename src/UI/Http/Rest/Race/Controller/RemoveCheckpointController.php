<?php

namespace App\UI\Http\Rest\Race\Controller;

use App\Application\Race\UseCase\RemoveCheckpoint\RemoveCheckpointCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/races/{raceId}/checkpoints/{id}', name: 'api.race.checkpoint.remove', methods: ['DELETE'])]
final class RemoveCheckpointController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(string $raceId, string $id): JsonResponse
    {
        $this->commandBus->dispatch(new RemoveCheckpointCommand(
            $id,
            $raceId,
            /* @phpstan-ignore-next-line */
            $this->getUser()->getUser()->id
        ));

        return new JsonResponse(
            [],
            Response::HTTP_NO_CONTENT,
            ['Location' => $this->urlGenerator->generate('api.race.get', ['id' => $raceId])]
        );
    }
}
