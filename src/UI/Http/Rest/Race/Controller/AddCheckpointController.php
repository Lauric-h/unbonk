<?php

namespace App\UI\Http\Rest\Race\Controller;

use App\Application\Race\UseCase\AddCheckpoint\AddCheckpointCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\SharedKernel\IdGenerator;
use App\UI\Http\Rest\Race\Request\AddCheckpointRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/races/{raceId}/checkpoints', name: 'app.race.checkpoint.add', methods: ['POST'])]
final class AddCheckpointController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly SerializerInterface $serializer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly IdGenerator $idGenerator,
    ) {
    }

    public function __invoke(Request $request, string $raceId): JsonResponse
    {
        $addCheckpointRequest = $this->serializer->deserialize($request->getContent(), AddCheckpointRequest::class, 'json');

        $id = $this->idGenerator->generate();
        $this->commandBus->dispatch(command: new AddCheckpointCommand(
            id: $id,
            name: $addCheckpointRequest->name,
            location: $addCheckpointRequest->location,
            checkpointType: $addCheckpointRequest->checkpointType,
            estimatedTimeInMinutes: $addCheckpointRequest->estimatedTimeInMinutes,
            distance: $addCheckpointRequest->distance,
            elevationGain: $addCheckpointRequest->elevationGain,
            elevationLoss: $addCheckpointRequest->elevationLoss,
            raceId: $raceId,
            /* @phpstan-ignore-next-line */
            runnerId: $this->getUser()->getUser()->id,
        ));

        return new JsonResponse(
            [],
            Response::HTTP_CREATED,
            ['Location' => $this->urlGenerator->generate('app.race.get', ['id' => $raceId])]
        );
    }
}
