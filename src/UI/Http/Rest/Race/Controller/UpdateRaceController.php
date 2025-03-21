<?php

namespace App\UI\Http\Rest\Race\Controller;

use App\Application\Race\UseCase\GetRace\GetRaceQuery;
use App\Application\Race\UseCase\UpdateRace\UpdateRaceCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Bus\QueryBus;
use App\UI\Http\Rest\Race\Request\UpdateRaceRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Clock\DatePoint;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/races/{id}', name: 'app.race.update', methods: ['PUT'])]
final class UpdateRaceController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly CommandBus $commandBus,
        private readonly QueryBus $queryBus,
    ) {
    }

    public function __invoke(string $id, Request $request): JsonResponse
    {
        $updateRequest = $this->serializer->deserialize($request->getContent(), UpdateRaceRequest::class, 'json');

        $command = new UpdateRaceCommand(
            $id,
            /* @phpstan-ignore-next-line */
            $this->getUser()->getUser()->id,
            new DatePoint($updateRequest->date),
            $updateRequest->name,
            $updateRequest->distance,
            $updateRequest->elevationGain,
            $updateRequest->elevationLoss,
            $updateRequest->city,
            $updateRequest->postalCode,
        );

        $this->commandBus->dispatch($command);

        /** @phpstan-ignore-next-line */
        $query = new GetRaceQuery($id, $this->getUser()->getUser()->id);

        return new JsonResponse(
            $this->queryBus->query($query),
            Response::HTTP_OK,
            ['Location' => $this->urlGenerator->generate('app.race.get', ['id' => $command->id])]
        );
    }
}
