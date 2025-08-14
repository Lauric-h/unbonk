<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\UseCase\DeleteRace\DeleteRaceCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/races/{id}/delete', name: 'app.race.delete')]
final class DeleteRaceController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(string $id): Response
    {
        /* @phpstan-ignore-next-line */
        $this->commandBus->dispatch(new DeleteRaceCommand($id, $this->getUser()->getUser()->id));

        return $this->redirectToRoute('app.race.list');
    }
}
