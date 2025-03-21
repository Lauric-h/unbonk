<?php

namespace App\UI\Http\Rest\Food\Food;

use App\Application\Food\UseCase\GetFood\GetFoodQuery;
use App\Application\Food\UseCase\UpdateFood\UpdateFoodCommand;
use App\Infrastructure\Shared\Bus\CommandBus;
use App\Infrastructure\Shared\Bus\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/foods/{id}', name: 'app.food.update', methods: ['PUT'])]
final class UpdateFoodController extends AbstractController
{
    public function __construct(
        private readonly CommandBus $messageBus,
        private readonly QueryBus $queryBus,
        private readonly SerializerInterface $serializer,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $command = $this->serializer->deserialize($request->getContent(), UpdateFoodCommand::class, 'json');
        $this->messageBus->dispatch($command);

        return new JsonResponse(
            $this->queryBus->query(new GetFoodQuery($command->id)),
            Response::HTTP_OK,
            ['Location' => $this->urlGenerator->generate('app.food.get', ['id' => $command->id])]
        );
    }
}
