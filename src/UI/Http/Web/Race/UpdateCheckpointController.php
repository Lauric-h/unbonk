<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\UseCase\GetCheckpoint\GetCheckpointQuery;
use App\Application\Race\UseCase\UpdateCheckpoint\UpdateCheckpointCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\UI\Http\Web\Race\Form\UpdateCheckpoint\UpdateCheckpointForm;
use App\UI\Http\Web\Race\Form\UpdateCheckpoint\UpdateCheckpointModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/races/{raceId}/checkpoints/{id}/update', name: 'app.race.checkpoint.update')]
final class UpdateCheckpointController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
    ) {
    }

    public function __invoke(Request $request, string $raceId, string $id): Response
    {
        $checkpoint = $this->queryBus->query(new GetCheckpointQuery($raceId, $id));

        $updateCheckpointModel = UpdateCheckpointModel::fromCheckpoint($checkpoint);
        $form = $this->createForm(UpdateCheckpointForm::class, $updateCheckpointModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->dispatch(new UpdateCheckpointCommand(
                id: $id,
                name: $updateCheckpointModel->name, // @phpstan-ignore-line
                location: $updateCheckpointModel->location, // @phpstan-ignore-line
                checkpointType: $updateCheckpointModel->checkpointType, // @phpstan-ignore-line
                estimatedTimeInMinutes: $updateCheckpointModel->estimatedTimeInMinutes, // @phpstan-ignore-line
                distance: $updateCheckpointModel->distance, // @phpstan-ignore-line
                elevationGain: $updateCheckpointModel->ascent, // @phpstan-ignore-line
                elevationLoss: $updateCheckpointModel->descent, // @phpstan-ignore-line
                raceId: $raceId,
                runnerId: $this->getUser()->getUser()->id // @phpstan-ignore-line
            ));

            return $this->redirectToRoute('app.race.get', ['id' => $raceId]);
        }

        return $this->render('Race/update_checkpoint.html.twig', [
            'form' => $form,
            'checkpoint' => $checkpoint,
        ]);
    }
}
