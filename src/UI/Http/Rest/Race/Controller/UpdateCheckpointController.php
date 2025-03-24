<?php

namespace App\UI\Http\Rest\Race\Controller;

use App\Application\Race\UseCase\UpdateCheckpoint\UpdateCheckpointCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\UI\Http\Rest\Race\Request\UpdateCheckpointRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/races/{raceId}/checkpoints/{id}', name: 'app.race.checkpoint.update', methods: ['PUT'])]
final class UpdateCheckpointController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function __invoke(Request $request, string $raceId, string $id): JsonResponse
    {
        $updateRequest = $this->serializer->deserialize($request->getContent(), UpdateCheckpointRequest::class, 'json');

        $this->commandBus->dispatch(new UpdateCheckpointCommand(
            id: $id,
            name: $updateRequest->name,
            location: $updateRequest->location,
            checkpointType: $updateRequest->checkpointType,
            estimatedTimeInMinutes: $updateRequest->estimatedTimeInMinutes,
            distance: $updateRequest->distance,
            elevationGain: $updateRequest->elevationGain,
            elevationLoss: $updateRequest->elevationLoss,
            raceId: $raceId,
            /* @phpstan-ignore-next-line */
            runnerId: $this->getUser()->getUser()->id,
        ));

        return new JsonResponse(
            [],
            Response::HTTP_NO_CONTENT,
            ['Location' => $this->urlGenerator->generate('app.race.get', ['id' => $raceId])],
        );
    }
}
