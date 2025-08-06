<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\UseCase\AddCheckpoint\AddCheckpointCommand;
use App\Application\Race\UseCase\GetRace\GetRaceQuery;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\SharedKernel\IdGenerator;
use App\UI\Http\Rest\Race\Request\AddCheckpointRequest;
use App\UI\Http\Web\Race\Form\AddCheckpoint\AddCheckpointForm;
use App\UI\Http\Web\Race\Form\AddCheckpoint\AddCheckpointModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

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
                name: $addCheckpointModel->name,
                location: $addCheckpointModel->location,
                checkpointType: $addCheckpointModel->checkpointType,
                estimatedTimeInMinutes: $addCheckpointModel->estimatedTimeInMinutes,
                distance: $addCheckpointModel->distance,
                ascent: $addCheckpointModel->ascent,
                descent: $addCheckpointModel->descent,
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
