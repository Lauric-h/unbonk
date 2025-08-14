<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\UseCase\RemoveCheckpoint\RemoveCheckpointCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/races/{raceId}/checkpoints/{id}/remove', name: 'app.race.checkpoint.remove')]
final class RemoveCheckpointController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
    ) {
    }

    public function __invoke(string $raceId, string $id): Response
    {
        $this->commandBus->dispatch(new RemoveCheckpointCommand(
            $id,
            $raceId,
            /* @phpstan-ignore-next-line */
            $this->getUser()->getUser()->id
        ));

        return $this->redirectToRoute('app.race.get', ['id' => $raceId]);
    }
}
