<?php

namespace App\UI\Http\Web\Race;

use App\Application\Race\UseCase\UpdateCheckpoint\UpdateCheckpointCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\UI\Http\Rest\Race\Request\UpdateCheckpointRequest;
use App\UI\Http\Web\Race\Form\UpdateCheckpoint\UpdateCheckpointForm;
use App\UI\Http\Web\Race\Form\UpdateCheckpoint\UpdateCheckpointModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/races/{raceId}/checkpoints/{id}/update', name: 'app.race.checkpoint.update')]
final class UpdateCheckpointController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
    ) {
    }

    public function __invoke(Request $request, string $raceId, string $id): JsonResponse
    {
        $checkpoint = $this->queryBus->query(new GetCheckpointQuery());

        $updateCheckpointModel = UpdateCheckpointModel::fromCheckpoint($checkpoint);
        $form = $this->createForm(UpdateCheckpointForm::class, $updateCheckpointModel);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->commandBus->dispatch(new UpdateCheckpointCommand(
                id: $id,
                name: $updateCheckpointModel->name,
                location: $updateCheckpointModel->location,
                checkpointType: $updateCheckpointModel->checkpointType,
                estimatedTimeInMinutes: $updateCheckpointModel->estimatedTimeInMinutes,
                distance: $updateCheckpointModel->distance,
                elevationGain: $updateCheckpointModel->ascent,
                elevationLoss: $updateCheckpointModel->descent,
                raceId: $raceId,
                /* @phpstan-ignore-next-line */
                runnerId: $this->getUser()->getUser()->id,
            ));
        }

        return $this->render('', [
           'form' => $form,
           'checkpoint' => $checkpoint,
        ]);
    }
}
