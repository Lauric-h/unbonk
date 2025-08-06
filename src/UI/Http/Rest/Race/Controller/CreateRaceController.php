<?php

namespace App\UI\Http\Rest\Race\Controller;

use App\Application\Race\UseCase\CreateRace\CreateRaceCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\SharedKernel\IdGenerator;
use App\UI\Http\Rest\Race\Request\CreateRaceRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/races', name: 'api.race.create', methods: ['POST'])]
final class CreateRaceController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly IdGenerator $idGenerator,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $id = $this->idGenerator->generate();
        $createRaceRequest = $this->serializer->deserialize($request->getContent(), CreateRaceRequest::class, 'json');
        /** @phpstan-ignore-next-line  */
        $runnerId = $this->getUser()->getUser()->id;

        $command = new CreateRaceCommand(
            id: $id,
            runnerId: $runnerId,
            date: new \DateTimeImmutable($createRaceRequest->date),
            name: $createRaceRequest->name,
            distance: $createRaceRequest->distance,
            elevationGain: $createRaceRequest->elevationGain,
            elevationLoss: $createRaceRequest->elevationLoss,
            city: $createRaceRequest->city,
            postalCode: $createRaceRequest->postalCode,
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse(
            [],
            Response::HTTP_CREATED,
            ['Location' => $this->urlGenerator->generate('app.race.get', ['id' => $id])]
        );
    }
}
