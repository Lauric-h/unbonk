<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\UseCase\AddCheckpoint\AddCheckpointCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\SharedKernel\IdGenerator;
use App\UI\Http\Web\Race\Form\AddCheckpoint\AddCheckpointForm;
use App\UI\Http\Web\Race\Form\AddCheckpoint\AddCheckpointModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/races/{raceId}/checkpoints/add', name: 'app.race.checkpoint.add')]
final class AddCheckpointController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly IdGenerator $idGenerator,
    ) {
    }

    public function __invoke(Request $request, string $raceId): Response
    {
        $addCheckpointModel = new AddCheckpointModel();
        $form = $this->createForm(AddCheckpointForm::class, $addCheckpointModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $this->idGenerator->generate();
            $this->commandBus->dispatch(command: new AddCheckpointCommand(
                id: $id,
                name: $addCheckpointModel->name, // @phpstan-ignore-line
                location: $addCheckpointModel->location, // @phpstan-ignore-line
                checkpointType: $addCheckpointModel->checkpointType, // @phpstan-ignore-line
                estimatedTimeInMinutes: $addCheckpointModel->estimatedTimeInMinutes, // @phpstan-ignore-line
                distance: $addCheckpointModel->distance, // @phpstan-ignore-line
                ascent: $addCheckpointModel->ascent, // @phpstan-ignore-line
                descent: $addCheckpointModel->descent, // @phpstan-ignore-line
                raceId: $raceId,
                runnerId: $runnerId = $this->getUser()->getUser()->id // @phpstan-ignore-line
            ));

            return $this->redirectToRoute('app.race.get', ['id' => $raceId]);
        }

        return $this->render('Race/add_checkpoint.html.twig', [
            'form' => $form,
        ]);
    }
}
